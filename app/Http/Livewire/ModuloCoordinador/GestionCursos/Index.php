<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionCursos;

use App\Models\Admision;
use App\Models\Ciclo;
use App\Models\Coordinador;
use App\Models\Curso;
use App\Models\CursoProgramaPlan;
use App\Models\CursoProgramaProceso;
use App\Models\Docente;
use App\Models\DocenteCurso;
use App\Models\NotaMatriculaCurso;
use App\Models\Plan;
use App\Models\Programa;
use App\Models\ProgramaProcesoGrupo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // trait para paginacion
    protected $paginationTheme = 'bootstrap'; // tema de paginacion

    public $search = ''; // Variable para almacenar la búsqueda
    public $modo = 'show'; // Variable para almacenar el modo (select, show)

    // variables del coordinador
    public $usuario; // Variable para almacenar el usuario
    public $trabajador; // Variable para almacenar el trabajador
    public $coordinador; // Variable para almacenar el coordinador

    // variables para los modales
    public $title_modal = 'Asignar Docente al Curso Seleccionado'; // Variable para almacenar el título del modal
    public $curso; // Variable para almacenar el curso
    public $id_curso; // Variable para almacenar el id del curso
    public $curso_programa_plan; // Variable para almacenar el curso_programa_plan
    public $id_curso_programa_plan; // Variable para almacenar el id del curso_programa_plan
    public $docentes; // Variable para almacenar el docente
    public $docente; // Variable para almacenar el docente del formulario del modal
    public $id_docente_curso; // Variable para almacenar el id del docente_curso
    public $grupo; // Variable para almacenar el grupo
    public $grupos; // Variable para almacenar los grupos
    public $proceso; // Variable para almacenar el proceso

    // variables para los filtros
    public $filtro_plan; // Variable para almacenar el plan de estudios
    public $plan_data; // Variable para almacenar el plan de estudios
    public $filtro_programa; // Variable para almacenar el programa
    public $programa_data; // Variable para almacenar el programa
    public $filtro_ciclo; // Variable para almacenar el ciclo
    public $ciclo_data; // Variable para almacenar el ciclo

    protected $queryString = [ // Variables de búsqueda
        'search' => ['except' => ''],
        'filtro_plan' => ['except' => '', 'as' => 'fp'],
        'plan_data' => ['except' => '', 'as' => 'plan'],
        'filtro_programa' => ['except' => '', 'as' => 'fpr'],
        'programa_data' => ['except' => '', 'as' => 'programa'],
        'filtro_ciclo' => ['except' => '', 'as' => 'fc'],
        'ciclo_data' => ['except' => '', 'as' => 'ciclo'],
    ];

    protected $listeners = [ // Eventos
        'cambiar_estado_docente_curso' => 'cambiar_estado_docente_curso',
        'eliminar_docente_asignado' => 'eliminar_docente_asignado',
    ];

    public function updated($propertyName) // Método que se ejecuta al modificar una variable
    {
        if($this->modo == 'select')
        {
            $this->validateOnly($propertyName, [ // Validación de los campos
                'docente' => 'required|integer',
                'proceso' => 'required|integer',
                'grupo' => 'required|integer'
            ]);
        }
    }

    public function mount()
    {
        $this->usuario = auth('usuario')->user();
        $this->trabajador = $this->usuario->trabajador_tipo_trabajador->trabajador;
        $this->coordinador = Coordinador::where('id_trabajador', $this->trabajador->id_trabajador)->first();
    }

    public function updatedSearch() // Método que se ejecuta al modificar la variable de búsqueda
    {
        $this->resetPage(); // Reseteamos la paginación
    }

    public function aplicar_filtro()
    {
        $this->plan_data = $this->filtro_plan; // Se almacena el plan de estudios
        $this->programa_data = $this->filtro_programa; // Se almacena el programa
        $this->ciclo_data = $this->filtro_ciclo; // Se almacena el ciclo
    }

    public function resetear_filtro()
    {
        $this->reset([
            'filtro_plan',
            'plan_data',
            'filtro_programa',
            'programa_data',
            'filtro_ciclo',
            'ciclo_data',
        ]);
    }

    public function limpiar_modal()
    {
        $this->reset([
            'title_modal',
            'curso',
            'id_curso',
            'curso_programa_plan',
            'id_curso_programa_plan',
            'proceso',
            'docentes',
            'docente',
            'grupo',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cancelar_modal()
    {
        $this->reset([
            'docente',
            'proceso',
            'grupo',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cargar_datos(CursoProgramaPlan $curso_programa_plan, $modo)
    {
        $this->modo = $modo; // Se almacena el modo
        if($this->modo == 'select')
        {
            $this->title_modal = 'Asignar Docentes al Curso Seleccionado'; // Se almacena el título del modal
        }
        else if ($this->modo == 'show')
        {
            $this->title_modal = 'Detalle del Cursos y Docentes Asignados'; // Se almacena el título del modal
        }
        $this->curso_programa_plan = $curso_programa_plan; // Se almacena el curso_programa_plan
        $this->id_curso_programa_plan = $curso_programa_plan->id_curso_programa_plan; // Se almacena el id del curso_programa_plan
        $this->curso = $curso_programa_plan->curso; // Se almacena el curso
        $this->id_curso = $curso_programa_plan->curso->id_curso; // Se almacena el id del curso
        $this->docentes = DocenteCurso::where('id_curso_programa_plan', $this->id_curso_programa_plan)->get(); // Se almacena el docente
    }

    public function asignar_docente()
    {
        $this->validate([ // Validación de los campos
            'docente' => 'required|integer',
            'proceso' => 'required|integer',
            'grupo' => 'required|integer'
        ]);

        // Se verifica si el docente ya está asignado al curso
        $docente_curso = DocenteCurso::where('id_curso_programa_plan', $this->id_curso_programa_plan)
            ->where('id_docente', $this->docente)
            ->where('id_admision', $this->proceso)
            ->where('id_programa_proceso_grupo', $this->grupo)
            ->where('docente_curso_estado', 1)
            ->first();
        if ($docente_curso) {
            // emitir alerta para mostrar mensaje de error
            $this->dispatchBrowserEvent('alerta_curso', [
                'title' => '¡Error!',
                'text' => 'El docente ya está asignado al curso.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // se verifica si ya existe docente asignado al curso en el mismo proceso y grupo
        $docente_curso = DocenteCurso::where('id_curso_programa_plan', $this->id_curso_programa_plan)
            ->where('id_admision', $this->proceso)
            ->where('id_programa_proceso_grupo', $this->grupo)
            ->where('docente_curso_estado', 1)
            ->first();
        if ($docente_curso) {
            // emitir alerta para mostrar mensaje de error
            $this->dispatchBrowserEvent('alerta_curso', [
                'title' => '¡Error!',
                'text' => 'Ya existe un docente asignado al curso en el mismo proceso y grupo seleccionado.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // registrar docente
        $docente_curso = new DocenteCurso();
        $docente_curso->id_docente = $this->docente;
        $docente_curso->id_curso_programa_plan = $this->id_curso_programa_plan;
        $docente_curso->id_admision = $this->proceso;
        $docente_curso->id_programa_proceso_grupo = $this->grupo;
        $docente_curso->docente_curso_fecha_creacion = date('Y-m-d H:i:s');
        $docente_curso->docente_curso_estado = 1;
        $docente_curso->save();

        // emitir alerta para mostrar mensaje de éxito
        $this->dispatchBrowserEvent('alerta_curso', [
            'title' => '¡Éxito!',
            'text' => 'El docente se asignó correctamente al curso.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // limpiamos el combo docente
        $this->cancelar_modal();

        // Se actualiza la lista de docentes
        $this->docentes = DocenteCurso::where('id_curso_programa_plan', $this->id_curso_programa_plan)->get(); // Se almacena el docente
    }

    public function alerta_cambiar_estado(DocenteCurso $docente_curso)
    {
        $this->id_docente_curso = $docente_curso->id_docente_curso;
        // emitir alerta para poder modificar el estado del docente
        $this->dispatchBrowserEvent('alerta_cambiar_estado_docente_curso', [
            'title' => '¡Advertencia!',
            'text' => '¿Está seguro que desea cambiar el estado del docente en el curso asignado?',
            'icon' => 'question',
            'showCancelButton' => true,
            'confirmButtonText' => 'Aceptar',
            'cancelButtonText' => 'Cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger',
            'metodo' => 'cambiar_estado_docente_curso',
        ]);
    }

    public function cambiar_estado_docente_curso()
    {
        $docente_curso = DocenteCurso::find($this->id_docente_curso);

        if ($docente_curso->docente_curso_estado == 1)
        {
            $docente_curso->docente_curso_estado = 0;
            $docente_curso->save();

            // emitir alerta para mostrar mensaje de éxito
            $this->dispatchBrowserEvent('alerta_curso', [
                'title' => '¡Éxito!',
                'text' => 'Docente desactivado correctamente del curso.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
        else if ($docente_curso->docente_curso_estado == 0)
        {
            // verificamos si no hay mas docentes activos en el curso
            $docente_curso_activo = DocenteCurso::where('id_curso_programa_plan', $docente_curso->id_curso_programa_plan)
                ->where('id_admision', $docente_curso->id_admision)
                ->where('id_programa_proceso_grupo', $docente_curso->id_programa_proceso_grupo)
                ->where('docente_curso_estado', 1)
                ->first();

            // si ya existen docentes activos en el curso no se puede activar al docente
            if ($docente_curso_activo) {
                // emitir alerta para mostrar mensaje de error
                $this->dispatchBrowserEvent('alerta_curso', [
                    'title' => '¡Error!',
                    'text' => 'No se puede activar el docente, ya existe un docente activo en el curso.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
                return;
            }

            // cambiamos el estado ha activo
            $docente_curso->docente_curso_estado = 1;
            $docente_curso->save();

            // emitir alerta para mostrar mensaje de éxito
            $this->dispatchBrowserEvent('alerta_curso', [
                'title' => '¡Éxito!',
                'text' => 'Docente activado correctamente en el curso.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }

        // Se actualiza la lista de docentes
        $this->docentes = DocenteCurso::where('id_curso_programa_plan', $this->id_curso_programa_plan)->get(); // Se almacena el docente
    }

    public function alerta_eliminar_docente_asignado(DocenteCurso $docente_curso)
    {
        // buscamos si el docente tiene notas del curso registrado
        $nota_matricula_curso = NotaMatriculaCurso::join('matricula_curso', 'nota_matricula_curso.id_matricula_curso', '=', 'matricula_curso.id_matricula_curso')
            ->join('matricula', 'matricula_curso.id_matricula', '=', 'matricula.id_matricula')
            ->where('matricula_curso.id_curso_programa_plan', $docente_curso->id_curso_programa_plan)
            ->where('nota_matricula_curso.id_docente', $docente_curso->id_docente)
            ->where('matricula.id_programa_proceso_grupo', $docente_curso->id_programa_proceso_grupo)
            ->get();
        // eqmitimos alerta para mostrar mensaje de error
        if ($nota_matricula_curso->count() > 0 ) {
            $this->dispatchBrowserEvent('alerta_curso', [
                'title' => '¡Error!',
                'text' => 'El docente tiene notas registradas en el curso, no se puede eliminar. En ese caso, desactive el docente.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        $this->id_docente_curso = $docente_curso->id_docente_curso;
        // emitir alerta para poder modificar el estado del docente
        $this->dispatchBrowserEvent('alerta_cambiar_estado_docente_curso', [
            'title' => '¡Advertencia!',
            'text' => '¿Está seguro que desea eliminar el docente del curso asignado?, una vez eliminado no podrá recuperarlo.',
            'icon' => 'question',
            'showCancelButton' => true,
            'confirmButtonText' => 'Aceptar',
            'cancelButtonText' => 'Cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger',
            'metodo' => 'eliminar_docente_asignado',
        ]);
    }

    public function eliminar_docente_asignado()
    {
        // eliminar docente
        $docente_curso = DocenteCurso::find($this->id_docente_curso);
        $docente_curso->delete();

        // emitir alerta para mostrar mensaje de éxito
        $this->dispatchBrowserEvent('alerta_curso', [
            'title' => '¡Éxito!',
            'text' => 'Docente eliminado correctamente del curso asignado.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // Se actualiza la lista de docentes
        $this->docentes = DocenteCurso::where('id_curso_programa_plan', $this->id_curso_programa_plan)->get(); // Se almacena el docente
    }

    public function render()
    {
        $cursos = CursoProgramaPlan::join('curso', 'curso_programa_plan.id_curso', '=', 'curso.id_curso')
                    ->join('ciclo', 'curso.id_ciclo', '=', 'ciclo.id_ciclo')
                    ->join('programa_plan', 'curso_programa_plan.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                    ->join('plan', 'programa_plan.id_plan', '=', 'plan.id_plan')
                    ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                    ->where(function ($query) {
                        $query->where('curso.curso_codigo', 'like', '%' . $this->search . '%')
                            ->orWhere('curso.curso_nombre', 'like', '%' . $this->search . '%');
                    })
                    ->where('programa.id_facultad', $this->coordinador->id_facultad)
                    ->where('programa_plan.id_plan', $this->plan_data == null ? '!=' : '=', $this->plan_data)
                    ->where('programa.id_programa', $this->programa_data == null ? '!=' : '=', $this->programa_data)
                    ->where('curso.id_ciclo', $this->ciclo_data == null ? '!=' : '=', $this->ciclo_data)
                    ->orderBy('ciclo.id_ciclo', 'asc')
                    ->orderBy('curso.curso_codigo', 'asc')
                    ->paginate(10);

        $planes = Plan::where('plan_estado', 1)->orderBy('plan', 'desc')->get();
        $procesos = Admision::orderBy('admision', 'desc')->get();
        $programas = Programa::query()
            ->where('id_facultad', $this->coordinador->id_facultad)
            ->where('programa_estado', 1)
            ->orderBy('id_modalidad')
            ->orderBy('id_programa')
            ->get();
        $programa_tipo_filtro = $this->filtro_programa ? Programa::find($this->filtro_programa)->programa_tipo : null;
        $ciclos = Ciclo::where('ciclo_estado', 1)
                    ->where(function ($query) use ($programa_tipo_filtro) {
                        $query->where('ciclo_programa', 0)
                            ->orWhere('ciclo_programa', $programa_tipo_filtro ? '=' : '!=' , $programa_tipo_filtro);
                    })
                    ->get();
        $docentes_model = Docente::join('trabajador', 'docente.id_trabajador', '=', 'trabajador.id_trabajador')
                                ->where('docente.docente_estado', 1)
                                ->get();

        $this->grupos = $this->curso_programa_plan ?
            ($this->proceso ?
                ProgramaProcesoGrupo::join('programa_proceso', 'programa_proceso_grupo.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                    ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                    ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                    ->where('programa_plan.id_programa_plan', $this->curso_programa_plan->id_programa_plan)
                    ->where('programa_proceso.id_admision', $this->proceso)
                    ->get() :
                collect()) :
            collect();

        return view('livewire.modulo-coordinador.gestion-cursos.index', [
            'cursos' => $cursos,
            'planes' => $planes,
            'procesos' => $procesos,
            'programas' => $programas,
            'ciclos' => $ciclos,
            'docentes_model' => $docentes_model,
        ]);
    }
}
