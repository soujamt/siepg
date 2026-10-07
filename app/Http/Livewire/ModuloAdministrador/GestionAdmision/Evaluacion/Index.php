<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\Evaluacion;

use App\Exports\reporte\moduloAdministrador\evaluacion\listaProgramasExport;
use App\Models\Evaluacion;
use App\Models\EvaluacionEntrevista;
use App\Models\EvaluacionEntrevistaItem;
use App\Models\EvaluacionExpediente;
use App\Models\EvaluacionExpedienteTitulo;
use App\Models\EvaluacionInvestigacion;
use App\Models\EvaluacionInvestigacionItem;
use App\Models\EvaluacionObservacion;
use App\Models\Inscripcion;
use App\Models\Programa;
use App\Models\Puntaje;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; //paginacion de bootstrap

    public $variable = 'expediente';
    public $es_doctorado = false;

    public $cant_paginas = 50;
    public $search = '';
    public $programa_filtro = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'programa_filtro' => ['except' => 0],
        'sort_nombre' => ['except' => 'nombre_completo'],
        'sort_direccion' => ['except' => 'asc'],
    ];

    public $inscripcion;
    public $evaluacion;
    public $puntaje;

    public Collection $evaluacion_expediente;
    public Collection $evaluacion_investigacion;
    public Collection $evaluacion_entrevista;
    public $puntajes_expediente = [];
    public $puntajes_investigacion = [];
    public $puntajes_entrevista = [];
    public $puntaje_total = 0;
    public $observacion;

    public $sort_nombre = 'nombre_completo'; // Columna de la tabla a ordenar
    public $sort_direccion = 'asc'; // Orden de la columna a ordenar

    public function mount()
    {
        $this->evaluacion_expediente = collect();
        $this->evaluacion_investigacion = collect();
        $this->evaluacion_entrevista = collect();
        $this->puntaje = Puntaje::where('puntaje_estado', 1)->first();
    }

    public function alerta($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function change_title($value)
    {
        $this->variable = $value;
        $this->cargar_puntaje();
    }

    public function limpiar()
    {
        $this->reset([
            'variable',
            'search',
            'inscripcion',
            'evaluacion',
            'puntajes_expediente',
            'puntajes_investigacion',
            'puntajes_entrevista',
            'puntaje_total',
            'observacion'
        ]);
        $this->evaluacion_expediente = collect();
        $this->evaluacion_investigacion = collect();
        $this->evaluacion_entrevista = collect();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cargar_evaluacion($id_inscripcion)
    {
        $this->limpiar();
        $this->inscripcion = Inscripcion::find($id_inscripcion);
        $this->es_doctorado = $this->inscripcion->inscripcion_tipo_programa == 2 ? true : false;
        // verificamos si tiene evaluacion
        $evaluacion = Evaluacion::where('id_inscripcion', $id_inscripcion)->first();
        if (!$evaluacion) {
            $evaluacion = new Evaluacion(); // se crea una nueva evaluacion
            $evaluacion->evaluacion_estado = 1;
            $evaluacion->evaluacion_estado_admitido = 0;
            $evaluacion->id_inscripcion = $this->inscripcion->id_inscripcion;
            $evaluacion->id_tipo_evaluacion = $this->inscripcion->inscripcion_tipo_programa;
            $evaluacion->save();
        }
        $this->evaluacion = $evaluacion;
        // cargamos las evaluaciones de expediente
        $this->evaluacion_expediente = EvaluacionExpedienteTitulo::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        $this->evaluacion_investigacion = EvaluacionInvestigacionItem::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        $this->evaluacion_entrevista = EvaluacionEntrevistaItem::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        $this->cargar_puntaje();
    }

    public function cargar_puntaje()
    {
        $this->puntaje_total = 0;
        $this->puntajes_expediente = [];
        $this->puntajes_investigacion = [];
        $this->puntajes_entrevista = [];
        if ($this->variable == 'expediente') {
            foreach ($this->evaluacion_expediente as $evaluacion) {
                $evaluacion_expediente = EvaluacionExpediente::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                    ->where('id_evaluacion_expediente_titulo', $evaluacion->id_evaluacion_expediente_titulo)
                    ->first();
                $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo] = $evaluacion_expediente ? $evaluacion_expediente->evaluacion_expediente_puntaje : 0;
                $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo] = number_format($this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo], 0);
                $this->puntaje_total += $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo];
            }
        } elseif ($this->variable == 'investigacion') {
            foreach ($this->evaluacion_investigacion as $evaluacion) {
                $evaluacion_investigacion = EvaluacionInvestigacion::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                    ->where('id_evaluacion_investigacion_item', $evaluacion->id_evaluacion_investigacion_item)
                    ->first();
                $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item] = $evaluacion_investigacion ? $evaluacion_investigacion->evaluacion_investigacion_puntaje : 0;
                $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item] = number_format($this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item], 0);
                $this->puntaje_total += $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item];
            }
        } elseif ($this->variable == 'entrevista') {
            foreach ($this->evaluacion_entrevista as $evaluacion) {
                $evaluacion_entrevista = EvaluacionEntrevista::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                    ->where('id_evaluacion_entrevista_item', $evaluacion->id_evaluacion_entrevista_item)
                    ->first();
                $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item] = $evaluacion_entrevista ? $evaluacion_entrevista->evaluacion_entrevista_puntaje : 0;
                $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item] = number_format($this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item], 0);
                $this->puntaje_total += $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item];
            }
        }
    }

    public function updatedPuntajesExpediente()
    {
        $this->puntaje_total = 0;
        foreach ($this->puntajes_expediente as $puntaje) {
            $this->puntaje_total += $puntaje;
        }
    }

    public function updatedPuntajesInvestigacion()
    {
        $this->puntaje_total = 0;
        foreach ($this->puntajes_investigacion as $puntaje) {
            $this->puntaje_total += $puntaje;
        }
    }

    public function updatedPuntajesEntrevista()
    {
        $this->puntaje_total = 0;
        foreach ($this->puntajes_entrevista as $puntaje) {
            $this->puntaje_total += $puntaje;
        }
    }

    public function evaluar_expediente()
    {
        if ($this->puntaje_total == 0) {
            $this->alerta('¡Error!', 'Debe ingresar al menos un puntaje.', 'error', 'Aceptar', 'danger');
            return;
        }

        // validar si no hay puntajes vacios
        foreach ($this->puntajes_expediente as $puntaje) {
            if ($puntaje == 0) {
                $this->alerta('¡Error!', 'Hay puntajes por ingresar', 'error', 'Aceptar', 'danger');
                return;
            }
        }

        // ingresamos los puntajes
        foreach ($this->evaluacion_expediente as $evaluacion) {
            $evaluacion_expediente = EvaluacionExpediente::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                ->where('id_evaluacion_expediente_titulo', $evaluacion->id_evaluacion_expediente_titulo)
                ->first();
            if (!$evaluacion_expediente) {
                $evaluacion_expediente = new EvaluacionExpediente();
                $evaluacion_expediente->evaluacion_expediente_puntaje = $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo];
                $evaluacion_expediente->id_evaluacion_expediente_titulo = $evaluacion->id_evaluacion_expediente_titulo;
                $evaluacion_expediente->id_evaluacion = $this->evaluacion->id_evaluacion;
            } else {
                $evaluacion_expediente->evaluacion_expediente_puntaje = $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo];
            }
            $evaluacion_expediente->save();
        }

        $this->evaluacion->puntaje_expediente = $this->puntaje_total;
        $this->evaluacion->fecha_expediente = date('Y-m-d H:i:s'); // fecha actual
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 1; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        finalizar_evaluacion($this->evaluacion, $this->puntaje);

        $this->alerta('¡Exito!', 'Se ha registrado el puntaje de su evaluación correctamente.', 'success', 'Aceptar', 'success');
    }

    public function evaluar_investigacion()
    {
        if ($this->puntaje_total == 0) {
            $this->alerta('¡Error!', 'Debe ingresar al menos un puntaje.', 'error', 'Aceptar', 'danger');
            return;
        }

        // validar si no hay puntajes vacios
        foreach ($this->puntajes_investigacion as $puntaje) {
            if ($puntaje == 0) {
                $this->alerta('¡Error!', 'Hay puntajes por ingresar', 'error', 'Aceptar', 'danger');
                return;
            }
        }

        // ingresamos los puntajes
        foreach ($this->evaluacion_investigacion as $evaluacion) {
            $evaluacion_investigacion = EvaluacionInvestigacion::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                ->where('id_evaluacion_investigacion_item', $evaluacion->id_evaluacion_investigacion_item)
                ->first();
            if (!$evaluacion_investigacion) {
                $evaluacion_investigacion = new EvaluacionInvestigacion();
                $evaluacion_investigacion->evaluacion_investigacion_puntaje = $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item];
                $evaluacion_investigacion->id_evaluacion_investigacion_item = $evaluacion->id_evaluacion_investigacion_item;
                $evaluacion_investigacion->id_evaluacion = $this->evaluacion->id_evaluacion;
            } else {
                $evaluacion_investigacion->evaluacion_investigacion_puntaje = $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item];
            }
            $evaluacion_investigacion->save();
        }

        $this->evaluacion->puntaje_investigacion = $this->puntaje_total;
        $this->evaluacion->fecha_investigacion = date('Y-m-d H:i:s'); // fecha actual
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 2; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        finalizar_evaluacion($this->evaluacion, $this->puntaje);

        $this->alerta('¡Exito!', 'Se ha registrado el puntaje de su evaluación correctamente.', 'success', 'Aceptar', 'success');
    }

    public function evaluar_entrevista()
    {
        if ($this->puntaje_total == 0) {
            $this->alerta('¡Error!', 'Debe ingresar al menos un puntaje.', 'error', 'Aceptar', 'danger');
            return;
        }

        // validar si no hay puntajes vacios
        foreach ($this->puntajes_entrevista as $puntaje) {
            if ($puntaje == 0) {
                $this->alerta('¡Error!', 'Hay puntajes por ingresar', 'error', 'Aceptar', 'danger');
                return;
            }
        }

        // ingresamos los puntajes
        foreach ($this->evaluacion_entrevista as $evaluacion) {
            $evaluacion_entrevista = EvaluacionEntrevista::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                ->where('id_evaluacion_entrevista_item', $evaluacion->id_evaluacion_entrevista_item)
                ->first();
            if (!$evaluacion_entrevista) {
                $evaluacion_entrevista = new EvaluacionEntrevista();
                $evaluacion_entrevista->evaluacion_entrevista_puntaje = $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item];
                $evaluacion_entrevista->id_evaluacion_entrevista_item = $evaluacion->id_evaluacion_entrevista_item;
                $evaluacion_entrevista->id_evaluacion = $this->evaluacion->id_evaluacion;
            } else {
                $evaluacion_entrevista->evaluacion_entrevista_puntaje = $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item];
            }
            $evaluacion_entrevista->save();
        }

        $this->evaluacion->puntaje_entrevista = $this->puntaje_total;
        $this->evaluacion->fecha_entrevista = date('Y-m-d H:i:s'); // fecha actual
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 3; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        finalizar_evaluacion($this->evaluacion, $this->puntaje);

        $this->alerta('¡Exito!', 'Se ha registrado el puntaje de su evaluación correctamente.', 'success', 'Aceptar', 'success');
    }

    public function exportar_excel()
    {
        // return (new listaProgramasExport)->download('listado-evaluaciones-por-programas.xlsx');
        return Excel::download(new listaProgramasExport, 'listado-evaluaciones-por-programas.xlsx');
    }

    public function ordenar_tabla($value)
    {
        if ($this->sort_nombre == $value) {
            if ($this->sort_direccion == 'asc') {
                $this->sort_direccion = 'desc';
            } else {
                $this->sort_direccion = 'asc';
            }
        } else {
            $this->sort_nombre = $value;
            $this->sort_direccion = 'asc';
        }
    }

    public function render()
    {
        $inscripciones = Inscripcion::join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
            ->join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
            ->leftJoin('evaluacion', 'inscripcion.id_inscripcion', '=', 'evaluacion.id_inscripcion')
            ->where('inscripcion.inscripcion_estado', 1)
            ->where('inscripcion.verificar_expedientes', 1)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('programa_proceso.id_admision', getAdmision()->id_admision)
            ->where(function ($query) {
                $query->where('persona.nombre_completo', 'like', '%' . $this->search . '%')
                    ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%');
            })
            ->where(function ($query) {
                if ($this->programa_filtro != 0) {
                    $query->where('programa.id_programa', $this->programa_filtro);
                }
            })
            ->select('inscripcion.*', 'persona.*', 'programa.*')
            ->orderBy($this->sort_nombre == 'nombre_completo' ? 'persona.' . $this->sort_nombre :'evaluacion.' .  $this->sort_nombre, $this->sort_direccion)
            ->paginate($this->cant_paginas);
        // programas ofertados en el proceso de admision activo
        $programas = Programa::join('programa_plan', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('programa_proceso', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->where('programa_proceso.id_admision', getAdmision()->id_admision)
            ->where('programa.programa_estado', 1)
            ->select('programa.*')
            ->distinct()
            ->orderBy('programa.id_programa')
            ->get();
        return view('livewire.modulo-administrador.gestion-admision.evaluacion.index', [
            'inscripciones' => $inscripciones,
            'programas' => $programas
        ]);
    }
}
