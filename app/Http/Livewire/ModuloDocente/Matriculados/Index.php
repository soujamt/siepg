<?php

namespace App\Http\Livewire\ModuloDocente\Matriculados;

use App\Models\Docente;
use Livewire\Component;
use App\Models\ActaDocente;
use Illuminate\Support\Str;
use App\Models\DocenteCurso;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CursoProgramaPlan;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\reporte\moduloDocente\matriculados\listaMatriculadosExport;
use App\Models\Matricula\MatriculaCurso as ModelMatriculaCurso;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Index extends Component
{
    public $id_docente_curso;
    public $docente_curso;
    public $id_programa_proceso_grupo;
    public $curso_programa_plan;
    public $id_curso_programa_plan;
    public $id_admision;
    public $curso;
    public $programa;
    public $grupo;

    public $id_matricula_curso;
    public $matricula_curso;
    public $nota_matricula_curso;
    public $modo_nota = 0;

    public $matriculados;
    public $notas = [];
    public $modo = 'hide';

    public int $matriculados_count = 0;
    public int $matriculados_finalizados_count = 0;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'modo' => ['except' => 'hide'],
    ];

    protected $listeners = [
        'asignar_nsp' => 'asignar_nsp',
    ];

    public function mount()
    {
        $this->docente_curso = $this->consultaDocenteCursoAutorizado()->first() ?? abort(403);

        $this->curso_programa_plan = CursoProgramaPlan::find($this->docente_curso->id_curso_programa_plan);
        $this->id_curso_programa_plan = $this->curso_programa_plan->id_curso_programa_plan;
        $this->id_programa_proceso_grupo = $this->docente_curso->id_programa_proceso_grupo;
        $this->id_admision = $this->docente_curso->id_admision;
        $this->curso = $this->curso_programa_plan->curso;
        $this->programa = $this->curso_programa_plan->programa_plan->programa;
        $this->grupo = $this->docente_curso->programa_proceso_grupo;
    }

    private function consultaDocenteCursoAutorizado(): Builder
    {
        $idTrabajador = auth('usuario')->user()?->trabajador_tipo_trabajador?->id_trabajador;
        abort_if($idTrabajador === null, 403);

        return DocenteCurso::query()
            ->whereKey($this->id_docente_curso)
            ->whereHas('docente', fn ($query) => $query->where('id_trabajador', $idTrabajador));
    }

    public function updated($propertyName)
    {
        // Limpiar los mensajes de error cuando se modifiquen los campos
        $this->resetErrorBag($propertyName);
    }

    public function modo_ingresar_notas()
    {
        // emitir alerta de que todas las notas ya fueron ingresadas
        if ( $this->matriculados_count == $this->matriculados_finalizados_count )
        {
            $this->dispatchBrowserEvent('alerta_matriculados', [
                'title' => '¡Alerta!',
                'text' => 'Todas las notas ya fueron ingresadas.',
                'icon' => 'warning',
                'confirmButtonText' => 'Aceptar',
                'color' => 'warning'
            ]);
        }

        $this->modo = 'show';
    }

    public function modo_cancelar()
    {
        $this->modo = 'hide';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function esNotaValida($idMatricula, $campo)
    {
        // Verificar si la nota es válida
        if (isset($this->notas[$idMatricula][$campo]))
        {
            $nota = $this->notas[$idMatricula][$campo];

            // Realiza las validaciones necesarias
            // Por ejemplo, verifica si la nota está dentro de un rango específico
            // Si cumple las condiciones, retorna true, de lo contrario, retorna false
            return ($nota >= 0 && $nota <= 20);
        }

        return false;
    }

    public function limpiar_modal()
    {
        $this->reset([
            'nota',
        ]);
    }

    public function alerta_asignar_nsp(ModelMatriculaCurso $matricula_curso)
    {
        $this->matricula_curso = $matricula_curso;
        $this->id_matricula_curso = $matricula_curso->id_matricula_curso;

        if ( $this->matricula_curso->estado == 3 ) {
            // emitimos la alerta para mostrar mensaje sobre que el alumno ya fue asignado su nsp
            $this->dispatchBrowserEvent('alerta_matriculados', [
                'title' => '¡Atención!',
                'text' => 'El estudiante ya fue asignado su NSP.',
                'icon' => 'warning',
                'confirmButtonText' => 'Aceptar',
                'color' => 'warning'
            ]);
            return;
        }

        // emitir alerta para asignar nsp
        $this->dispatchBrowserEvent('alerta_matriculados_opciones', [
            'title' => '¡Atención!',
            'text' => '¿Está seguro que desea asignar NSP a este estudiante?',
            'icon' => 'warning',
            'confirmButtonText' => 'Si',
            'cancelButtonText' => 'Cancelar',
            'color' => 'warning',
            'confirmButtonColor' => 'warning',
            'cancelButtonColor' => 'danger',
        ]);
    }

    public function asignar_nsp()
    {
        $this->matricula_curso->nota_evaluacion_permanente = 0;
        $this->matricula_curso->nota_evaluacion_medio_curso = 0;
        $this->matricula_curso->nota_evaluacion_final = 0;
        $this->matricula_curso->nota_promedio_final = 0;
        $this->matricula_curso->id_docente = $this->docente_curso->id_docente;
        $this->matricula_curso->estado = 3;
        $this->matricula_curso->save();

        $this->finalizar_curso();

        // emitimos alerta para mostrar mensaje de éxito
        $this->dispatchBrowserEvent('alerta_matriculados', [
            'title' => '¡Éxito!',
            'text' => 'Se asignó el NSP correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function updatedNota($value)
    {
        // Validar los campos
        $this->validate([
            'nota' => 'required|numeric|between:0,20',
        ]);
    }

    public function guardar_notas()
    {
        // obtenemos los que tienen nsp
        $countMatriculadosNsp = ModelMatriculaCurso::query()
                        ->where('id_curso_programa_plan', $this->id_curso_programa_plan)
                        ->where('id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                        ->where('estado', 3)
                        ->where('activo', 1)
                        ->count();

        $count_matriculados_sin_notas = $this->matriculados_count - $countMatriculadosNsp;

        // validar si todos los campos de notas están vacíos y mostrar alerta
        $this->validate([
            'notas' => 'required|array|min:' . $count_matriculados_sin_notas,
        ]);

        // validar todas las filas de notas
        $this->validate([
            'notas.*.nota1' => 'required|numeric|between:0,20',
            'notas.*.nota2' => 'required|numeric|between:0,20',
            'notas.*.nota3' => 'required|numeric|between:0,20',
        ]);

        // registrar las notas en la tabla nota_matricula_curso
        foreach ($this->notas as $key => $item)
        {
            $matriculaCurso = ModelMatriculaCurso::find($key);
            $matriculaCurso->id_docente = $this->docente_curso->id_docente;
            $matriculaCurso->nota_evaluacion_permanente = $item['nota1'];
            $matriculaCurso->nota_evaluacion_medio_curso = $item['nota2'];
            $matriculaCurso->nota_evaluacion_final = $item['nota3'];
            $matriculaCurso->nota_promedio_final = calcularPromedio($item['nota1'], $item['nota2'], $item['nota3']);
            $matriculaCurso->estado = $matriculaCurso->nota_promedio_final >= 14 ? 2 : 0;
            $matriculaCurso->save();

            // calcular creditos acumulados
            calcularCreditosAcumulados($matriculaCurso->matricula->id_admitido);
        }

        $this->finalizar_curso();

        // emitir alerta de notas agregadas correctamente
        $this->dispatchBrowserEvent('alerta_matriculados', [
            'title' => '¡Éxito!',
            'text' => 'Se guardaron las notas correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // emitir evento para renderizar la tabla
        $this->emit('render');
    }

    public function finalizar_curso()
    {
        $matriculados_count = cantidadAlumnosMatriculadosCurso($this->id_curso_programa_plan, $this->id_programa_proceso_grupo);
        $matriculados_finalizados_count = cantidadAlumnosMatriculadosCursoFinalizado($this->id_curso_programa_plan, $this->id_programa_proceso_grupo);

        // emitir alerta de que todas las notas ya fueron ingresadas
        if ( $matriculados_count == $matriculados_finalizados_count )
        {
            // cambiamos el estado del curso del docente a finalizado
            $this->docente_curso->docente_curso_estado = 2; // 2 = curso finalizado
            $this->docente_curso->save();
        }

        $this->emit('render');
    }

    public function generar_acta_notas()
    {
        $rutasGeneradas = [];

        try {
            $generada = DB::transaction(function () use (&$rutasGeneradas) {
                $docenteCurso = $this->consultaDocenteCursoAutorizado()->lockForUpdate()->first() ?? abort(403);
                $idPlan = $docenteCurso->id_curso_programa_plan;
                $idGrupo = $docenteCurso->id_programa_proceso_grupo;
                abort_unless(
                    cantidadAlumnosMatriculadosCurso($idPlan, $idGrupo) === cantidadAlumnosMatriculadosCursoFinalizado($idPlan, $idGrupo),
                    422,
                    'Aún hay notas pendientes.'
                );

                $actas = $this->datosActas($docenteCurso);
                if ($actas === []) {
                    return false;
                }

                $directorio = 'Posgrado/Docente/Actas/';
                File::ensureDirectoryExists(public_path($directorio));
                $fecha = now();
                $actasVigentes = ActaDocente::query()
                    ->where('id_docente_curso', $docenteCurso->getKey())
                    ->where('acta_docente_estado', 1);

                if ($actasVigentes->update(['acta_docente_estado' => 0]) === 0) {
                    ModelMatriculaCurso::query()
                        ->where('id_curso_programa_plan', $idPlan)
                        ->where('id_programa_proceso_grupo', $idGrupo)
                        ->where('id_docente', $docenteCurso->id_docente)
                        ->where('activo', 1)
                        ->whereNull('fecha_ingreso_nota')
                        ->update(['fecha_ingreso_nota' => $fecha->toDateString()]);
                }

                foreach ($actas as $datos) {
                    $tipo = $datos['tipo'];
                    $codigo = $datos[$tipo]->admitido_codigo ?? null;
                    $nombre = implode('-', array_filter([
                        'acta-notas', $tipo, $codigo, $fecha->format('dmYHis'),
                        Str::slug($datos['docente']), Str::random(10),
                    ])) . '.pdf';
                    $ruta = $directorio . $nombre;
                    $rutaAbsoluta = public_path($ruta);
                    $rutasGeneradas[] = $rutaAbsoluta;

                    $pdf = Pdf::loadView('modulo-docente.acta-evaluacion.ficha-acta-evaluacion', $datos)->output();
                    if (File::put($rutaAbsoluta, $pdf) !== strlen($pdf)) {
                        throw new \RuntimeException('No se pudo guardar el acta de evaluación.');
                    }

                    ActaDocente::create([
                        'acta_url' => $ruta,
                        'id_docente_curso' => $docenteCurso->getKey(),
                        'acta_docente_fecha_creacion' => $fecha->toDateTimeString(),
                        'acta_docente_estado' => 1,
                        'es_' . $tipo => 1,
                    ]);
                }

                return true;
            });
        } catch (\Throwable $error) {
            File::delete($rutasGeneradas);
            throw $error;
        }

        $this->dispatchBrowserEvent('alerta_matriculados', [
            'title' => $generada ? '¡Éxito!' : 'Sin notas registradas',
            'text' => $generada ? 'Se generó el acta de notas correctamente.' : 'No hay notas registradas para generar el acta.',
            'icon' => $generada ? 'success' : 'warning',
            'confirmButtonText' => 'Aceptar',
            'color' => $generada ? 'success' : 'warning',
        ]);
    }

    private function datosActas(DocenteCurso $docenteCurso): array
    {
        $cursoProgramaPlan = $docenteCurso->curso_programa_plan;
        $curso = $cursoProgramaPlan->curso;
        $programa = $cursoProgramaPlan->programa_plan->programa;
        $trabajador = $docenteCurso->docente->trabajador;

        $datosCurso = [
            'programa' => $programa->programa_tipo == 1 ? 'Maestría' : 'Doctorado',
            'subprograma' => $programa->subprograma,
            'mencion' => $programa->mencion ?? '',
            'curso' => strtoupper($curso->curso_nombre),
            'codigo_curso' => $curso->curso_codigo,
            'docente' => $trabajador->grado_academico->grado_academico_prefijo . ' ' . strtoupper($trabajador->trabajador_nombre_completo),
            'codigo_docente' => $trabajador->trabajador_numero_documento,
            'creditos' => $curso->curso_credito,
            'ciclo' => $curso->ciclo->ciclo,
            'grupo' => $docenteCurso->programa_proceso_grupo->grupo_detalle,
            'admision_año' => $docenteCurso->admision->admision_año,
        ];

        $consulta = ModelMatriculaCurso::query()
            ->select('tbl_matricula_curso.*', 'admitido.admitido_codigo', 'persona.nombre_completo')
            ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
            ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->where('admitido.admitido_estado', 1)
            ->where('tbl_matricula_curso.id_curso_programa_plan', $docenteCurso->id_curso_programa_plan)
            ->where('tbl_matricula_curso.id_programa_proceso_grupo', $docenteCurso->id_programa_proceso_grupo)
            ->where('tbl_matricula_curso.id_docente', $docenteCurso->id_docente)
            // Otra matrícula puede desactivar este registro después de que se evaluó el curso.
            ->where(function ($query) {
                $query->whereNotNull('tbl_matricula_curso.nota_promedio_final')
                    ->orWhere('tbl_matricula_curso.estado', 3);
            })
            ->orderBy('persona.nombre_completo');

        $tipos = [
            'regular' => [0, 0, 0],
            'adicional' => [1, 0, 0],
            'reingreso' => [0, 1, 0],
            'incorporacion' => [0, 0, 1],
        ];
        $actas = [];

        foreach ($tipos as $tipo => [$adicional, $reingreso, $incorporacion]) {
            $matriculados = (clone $consulta)->where([
                'tbl_matricula_curso.es_acta_adicional' => $adicional,
                'tbl_matricula_curso.es_acta_reingreso' => $reingreso,
                'tbl_matricula_curso.es_acta_incorporacion' => $incorporacion,
            ])->get();

            if ($matriculados->isEmpty()) {
                continue;
            }

            $datos = $datosCurso + ['tipo' => $tipo];
            if ($tipo === 'regular' || $tipo === 'adicional') {
                $clave = $tipo === 'regular' ? 'matriculados' : 'matriculados_adicional';
                $actas[] = $datos + [$clave => $matriculados];
                continue;
            }

            foreach ($matriculados as $matriculado) {
                $actas[] = $datos + [$tipo => $matriculado];
            }
        }

        return $actas;
    }

    public function descargar_actas()
    {
        $docenteCurso = $this->consultaDocenteCursoAutorizado()->first() ?? abort(403);
        $archivos = ActaDocente::query()
            ->where('id_docente_curso', $docenteCurso->getKey())
            ->where('acta_docente_estado', 1)
            ->pluck('acta_url')
            ->map(fn ($ruta) => ['nombre' => basename($ruta), 'url' => asset($ruta)])
            ->all();

        $this->dispatchBrowserEvent('descargar_actas', ['array_archivos' => $archivos]);
    }

    public function exportar_excel_lista_matriculados()
    {
        $docenteCurso = DocenteCurso::find($this->id_docente_curso);
        $docente = Docente::find($docenteCurso->id_docente);

        $matriculados = ModelMatriculaCurso::query()
            ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
            ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->where('admitido.admitido_estado', 1)
            ->where('tbl_matricula_curso.id_curso_programa_plan', $this->id_curso_programa_plan)
            ->where('tbl_matricula_curso.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
            // ->where('tbl_matricula_curso.activo', 1)
            ->where('tbl_matricula_curso.id_docente', $docente->id_docente)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        if ( $matriculados->count() == 0 ) {
            $matriculados = ModelMatriculaCurso::query()
                ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
                ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
                ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                ->where('admitido.admitido_estado', 1)
                ->where('tbl_matricula_curso.id_curso_programa_plan', $this->id_curso_programa_plan)
                ->where('tbl_matricula_curso.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                ->where('tbl_matricula_curso.activo', 1)
                ->orderBy('persona.nombre_completo', 'asc')
                ->get();
        }

        $nombre_programa = $this->programa->programa . 'EN '. $this->programa->subprograma . ($this->programa->mencion ? ' CON MENCION EN ' . $this->programa->mencion : '');
        $nombre_programa = Str::slug($nombre_programa, '-');
        $nombre_file = 'listado-matriculados-'.$nombre_programa.'-'.date('dmYHis').'.xlsx';
        return Excel::download(new listaMatriculadosExport($matriculados, $this->programa, $this->curso, $this->grupo), $nombre_file);
    }

    public function render()
    {
        $docenteCurso = DocenteCurso::find($this->id_docente_curso);

        if ( $docenteCurso->docente_curso_estado == 2 ) {
            $docente = Docente::find($docenteCurso->id_docente);
            
            $this->matriculados = ModelMatriculaCurso::query()
                ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
                ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
                ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                ->where('admitido.admitido_estado', 1)
                ->where('tbl_matricula_curso.id_curso_programa_plan', $this->id_curso_programa_plan)
                ->where('tbl_matricula_curso.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                // ->where('tbl_matricula_curso.activo', 1)
                ->where('persona.nombre_completo', 'like', '%'.$this->search.'%')
                ->where('tbl_matricula_curso.id_docente', $docente->id_docente)
                ->orderBy('persona.nombre_completo', 'asc')
                ->select('tbl_matricula_curso.*')
                ->get();

            if ( $this->matriculados->count() == 0 ) {
                $this->matriculados = ModelMatriculaCurso::query()
                    ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
                    ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
                    ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                    ->where('admitido.admitido_estado', 1)
                    ->where('tbl_matricula_curso.id_curso_programa_plan', $this->id_curso_programa_plan)
                    ->where('tbl_matricula_curso.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                    ->where('tbl_matricula_curso.activo', 1)
                    ->where('persona.nombre_completo', 'like', '%'.$this->search.'%')
                    ->orderBy('persona.nombre_completo', 'asc')
                    ->select('tbl_matricula_curso.*')
                    ->get();
            }
        } else {
            $this->matriculados = ModelMatriculaCurso::query()
                ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
                ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
                ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                ->where('admitido.admitido_estado', 1)
                ->where('tbl_matricula_curso.id_curso_programa_plan', $this->id_curso_programa_plan)
                ->where('tbl_matricula_curso.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                ->where('tbl_matricula_curso.activo', 1)
                ->where('persona.nombre_completo', 'like', '%'.$this->search.'%')
                ->orderBy('persona.nombre_completo', 'asc')
                ->select('tbl_matricula_curso.*')
                ->get();
        }


        $this->matriculados_count = cantidadAlumnosMatriculadosCurso($this->id_curso_programa_plan, $this->id_programa_proceso_grupo);
        $this->matriculados_finalizados_count = cantidadAlumnosMatriculadosCursoFinalizado($this->id_curso_programa_plan, $this->id_programa_proceso_grupo);

        // verificar si ya se agregaron todas las notas
        if ( $this->matriculados_count == $this->matriculados_finalizados_count )
        {
            $this->modo = 'hide';
        }

        $mostrar_acta = ActaDocente::query()
            ->where('id_docente_curso', $this->id_docente_curso)
            ->where('acta_docente_estado', 1)
            ->exists();

        return view('livewire.modulo-docente.matriculados.index', [
            'mostrar_acta' => $mostrar_acta,
        ]);
    }
}
