<?php

namespace App\Http\Livewire\ModuloAreaContable\Inicio;

use App\Models\Admision;
use App\Models\Inscripcion;
use App\Models\Pago;
use Livewire\Component;

class Index extends Component
{
    public $admisiones, $admision; // variable para almacenar las admisiones
    public $filtro_proceso; // variable para filtrar por proceso de admision
    public $ingreso_total, $registro_total, $inscripcion_total, $constancia_total, $matricula_total; // variables para almacenar los totales
    public $programas_maestria, $programas_doctorado; // variables para almacenar los programas

    public function mount()
    {
        $this->admisiones = Admision::all();
        $admision = Admision::where('admision_estado', 1)->first();
        $this->filtro_proceso = $admision->id_admision;
        $this->admision = Admision::where('id_admision', $this->filtro_proceso)->first();
        $this->ingreso_total = Pago::sum('pago_monto');
        $this->constancia_total = Pago::where('pago_estado', 3)->sum('pago_monto');
        $this->matricula_total = Pago::where('pago_estado', 4)->sum('pago_monto');
        $this->registro_total = Pago::count();
        $this->inscripcion_total = Inscripcion::join('pago', 'pago.id_pago', '=', 'inscripcion.id_pago')
                                        ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->where('programa_proceso.id_admision', $this->filtro_proceso)
                                        ->whereNull('inscripcion.id_inscripcion_origen') // el pago de una inscripcion trasladada se cuenta en su proceso de origen
                                        ->sum('pago.pago_monto');
        $this->programas_maestria = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                        ->join('programa','programa_plan.id_programa','=','programa.id_programa')
                                        ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'))
                                        ->where('programa.programa_estado',1)
                                        ->where('programa.programa_tipo',1) // 1 = Maestria
                                        ->where('programa_proceso.id_admision', $this->filtro_proceso)
                                        ->groupBy('inscripcion.id_programa_proceso')
                                        ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'desc')
                                        ->get();
        $this->programas_doctorado = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                        ->join('programa','programa_plan.id_programa','=','programa.id_programa')
                                        ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'))
                                        ->where('programa.programa_estado',1)
                                        ->where('programa.programa_tipo',2) // 1 = Doctorado
                                        ->where('programa_proceso.id_admision', $this->filtro_proceso)
                                        ->groupBy('inscripcion.id_programa_proceso')
                                        ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'desc')
                                        ->get();
    }

    public function aplicar_filtro()
    {
        if($this->filtro_proceso == null || $this->filtro_proceso == "")
        {
            $this->mount();
        }
        else
        {
            $this->admision = Admision::where('id_admision', $this->filtro_proceso)->first();
            $this->inscripcion_total = Inscripcion::join('pago', 'pago.id_pago', '=', 'inscripcion.id_pago')
                                        ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->where('programa_proceso.id_admision', $this->filtro_proceso)
                                        ->whereNull('inscripcion.id_inscripcion_origen') // el pago de una inscripcion trasladada se cuenta en su proceso de origen
                                        ->sum('pago.pago_monto');
            $this->programas_maestria = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                        ->join('programa','programa_plan.id_programa','=','programa.id_programa')
                                        ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'))
                                        ->where('programa.programa_estado',1)
                                        ->where('programa.programa_tipo',1) // 1 = Maestria
                                        ->where('programa_proceso.id_admision', $this->filtro_proceso)
                                        ->groupBy('inscripcion.id_programa_proceso')
                                        ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'DESC')
                                        ->get();
            $this->programas_doctorado = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                        ->join('programa','programa_plan.id_programa','=','programa.id_programa')
                                        ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'))
                                        ->where('programa.programa_estado',1)
                                        ->where('programa.programa_tipo',2) // 1 = Doctorado
                                        ->where('programa_proceso.id_admision', $this->filtro_proceso)
                                        ->groupBy('inscripcion.id_programa_proceso')
                                        ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'DESC')
                                        ->get();
        }
    }

    public function resetear_filtro()
    {
        $this->mount();
    }

    public function render()
    {
        return view('livewire.modulo-area-contable.inicio.index');
    }
}
