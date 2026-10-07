<?php

namespace App\Http\Livewire\ModuloAdministrador\Dashboard;

use App\Exports\reporte\moduloAdministrador\matriculados\listaGruposExport;
use App\Models\Admision;
use App\Models\Admitido;
use App\Models\ConstanciaIngreso;
use App\Models\Inscripcion;
use App\Models\Matricula\Matricula as MatriculaMatricula;
use App\Models\Mensualidad;
use App\Models\Pago;
use App\Models\ProgramaProceso;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use NumberFormatter;

class Index extends Component
{

    public $admisiones, $admision; // variable para almacenar las admisiones y filtrar
    public $filtro_proceso; // variable para filtrar por proceso de admision
    public $filtro_proceso_data;
    public $ingreso_total, $ingreso_inscripcion, $ingreso_constancia, $ingreso_matricula; // variables para los totales
    public $ingreso_por_dia_total, $ingreso_por_dia_inscripcion, $ingreso_por_dia_constancia, $ingreso_por_dia_matricula; // Variables para las cantidades diarias
    public $ingreso_costo_enseñanza, $ingreso_por_dia_costo_enseñanza;
    public $programas_maestria, $programas_doctorado; // variables para almacenar los programas
    public $matriculados_programas; // variables para almacenar los matriculados
    public $proceso, $programa;

    protected $queryString = ['filtro_proceso', 'filtro_proceso_data'];

    public function mount()
    {
        $this->admisiones = Admision::query()
            ->orderBy('admision_año', 'desc')
            ->get();


        $admision = Admision::where('admision_estado', 1)->first();
        $this->admision = Admision::where('id_admision', $admision->id_admision)->first();
        $this->filtro_proceso = $admision->id_admision;
        $this->filtro_proceso_data = $admision->id_admision;

        $this->calcularMontos();
    }

    public function calcularMontos()
    {
        $this->ingreso_total = Pago::where('pago_estado', 2)
            ->where('pago_verificacion', 2)
            ->sum('pago_monto');

        $filtro_proceso_data = $this->filtro_proceso_data;

        // Se calcula el ingreso por concepto de constancias
        $ingreso_constancia_map = ConstanciaIngreso::query()
            ->with([
                'pago' => function ($query) {
                    $query->where('pago_estado', 2)
                        ->where('pago_verificacion', 2)
                        ->where('id_concepto_pago', 2);
                },
                'admitido' => function ($query) use ($filtro_proceso_data) {
                    $query->with([
                        'programa_proceso' => function ($query) use ($filtro_proceso_data) {
                            $query->where('id_admision', $filtro_proceso_data);
                        }
                    ]);
                }
            ])
            ->whereHas('pago', function ($query) {
                $query->where('pago_estado', 2)
                    ->where('pago_verificacion', 2)
                    ->where('id_concepto_pago', 2);
            })
            ->whereHas('admitido.programa_proceso', function ($query) use ($filtro_proceso_data) {
                $query->where('id_admision', $filtro_proceso_data);
            })
            ->get();
        $ingreso_constancia_sum = 0;
        foreach ($ingreso_constancia_map as $item) {
            $ingreso_constancia_sum += $item->pago->pago_monto;
        }
        $this->ingreso_constancia = $ingreso_constancia_sum;

        $ingreso_constancia_matricula_map = ConstanciaIngreso::query()
            ->with([
                'pago' => function ($query) {
                    $query->where('pago_estado', 2)
                        ->where('pago_verificacion', 2)
                        ->where('id_concepto_pago', 4);
                },
                'admitido' => function ($query) use ($filtro_proceso_data) {
                    $query->with([
                        'programa_proceso' => function ($query) use ($filtro_proceso_data) {
                            $query->where('id_admision', $filtro_proceso_data);
                        }
                    ]);
                }
            ])
            ->whereHas('pago', function ($query) {
                $query->where('pago_estado', 2)
                    ->where('pago_verificacion', 2)
                    ->where('id_concepto_pago', 4);
            })
            ->whereHas('admitido.programa_proceso', function ($query) use ($filtro_proceso_data) {
                $query->where('id_admision', $filtro_proceso_data);
            })
            ->get();
        $ingreso_constancia_matricula_sum = 0;
        foreach ($ingreso_constancia_matricula_map as $item) {
            $ingreso_constancia_matricula_sum += $item->pago->pago_monto;
        }

        $ingreso_constancia_matricula_extemporanea_map = ConstanciaIngreso::query()
            ->with([
                'pago' => function ($query) {
                    $query->where('pago_estado', 2)
                        ->where('pago_verificacion', 2)
                        ->where('id_concepto_pago', 6);
                },
                'admitido' => function ($query) use ($filtro_proceso_data) {
                    $query->with([
                        'programa_proceso' => function ($query) use ($filtro_proceso_data) {
                            $query->where('id_admision', $filtro_proceso_data);
                        }
                    ]);
                }
            ])
            ->whereHas('pago', function ($query) {
                $query->where('pago_estado', 2)
                    ->where('pago_verificacion', 2)
                    ->where('id_concepto_pago', 6);
            })
            ->whereHas('admitido.programa_proceso', function ($query) use ($filtro_proceso_data) {
                $query->where('id_admision', $filtro_proceso_data);
            })
            ->get();
        $ingreso_constancia_matricula_extemporanea_sum = 0;
        foreach ($ingreso_constancia_matricula_extemporanea_map as $item) {
            $ingreso_constancia_matricula_extemporanea_sum += $item->pago->pago_monto;
        }

        $sum_constancia_matricula = $ingreso_constancia_matricula_sum;
        $count_constancia_matricula = $ingreso_constancia_matricula_map->count();
        $diferencia_constancia_matricula = $sum_constancia_matricula - ($count_constancia_matricula * 150);
        $sum_constancia_matricula_extemporanea = $ingreso_constancia_matricula_extemporanea_sum;
        $count_constancia_matricula_extemporanea = $ingreso_constancia_matricula_extemporanea_map->count();
        $diferencia_constancia_matricula_extemporanea = $sum_constancia_matricula_extemporanea - ($count_constancia_matricula_extemporanea * 200);
        $this->ingreso_constancia = $this->ingreso_constancia + $diferencia_constancia_matricula + $diferencia_constancia_matricula_extemporanea;

        // Se calcula el ingreso por concepto de matriculas
        $ingreso_matricula_map = MatriculaMatricula::query()
            ->with([
                'pago' => function ($query) {
                    $query->where('pago_estado', 2)
                        ->where('pago_verificacion', 2)
                        ->where(function($query){
                            $query->where('id_concepto_pago', 3)
                                ->orWhere('id_concepto_pago', 5);
                        });
                },
                'admitido' => function ($query) use ($filtro_proceso_data) {
                    $query->with([
                        'programa_proceso' => function ($query) use ($filtro_proceso_data) {
                            $query->where('id_admision', $filtro_proceso_data);
                        }
                    ]);
                }
            ])
            ->whereHas('pago', function ($query) {
                $query->where('pago_estado', 2)
                    ->where('pago_verificacion', 2)
                    ->where(function($query){
                        $query->where('id_concepto_pago', 3)
                            ->orWhere('id_concepto_pago', 5);
                    });
            })
            ->whereHas('admitido.programa_proceso', function ($query) use ($filtro_proceso_data) {
                $query->where('id_admision', $filtro_proceso_data);
            })
            ->get();
        $ingreso_matricula_sum = 0;
        foreach ($ingreso_matricula_map as $item) {
            $ingreso_matricula_sum += $item->pago->pago_monto;
        }

        $diferencia_matricula_constancia = $sum_constancia_matricula - ($count_constancia_matricula * 30);
        $diferencia_matricula_constancia_extemporanea = $sum_constancia_matricula_extemporanea - ($count_constancia_matricula_extemporanea * 30);
        $this->ingreso_matricula = $ingreso_matricula_sum + $diferencia_matricula_constancia + $diferencia_matricula_constancia_extemporanea;

        // Se calcula el ingreso por concepto de inscripciones
        // el pago de una inscripcion trasladada a otro proceso se cuenta solo en su proceso de origen
        $this->ingreso_inscripcion = Inscripcion::join('pago', 'pago.id_pago', '=', 'inscripcion.id_pago')
            ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->where('programa_proceso.id_admision', $this->filtro_proceso_data)
            ->whereNull('inscripcion.id_inscripcion_origen')
            ->where(function ($query) {
                $query->where('inscripcion.retiro_inscripcion', 0)
                    ->orWhereIn('inscripcion.id_inscripcion', Inscripcion::whereNotNull('id_inscripcion_origen')->select('id_inscripcion_origen'));
            })
            ->where('pago.pago_estado', 2)
            ->where('pago.pago_verificacion', 2)
            ->sum('pago.pago_monto');

        $this->ingreso_por_dia_total = Pago::whereDate('pago_fecha', Carbon::today())
            ->where('pago_estado', 2)
            ->where('pago_verificacion', 2)
            ->sum('pago_monto');
        $this->ingreso_por_dia_constancia = Pago::where('id_concepto_pago', 2)
            ->whereDate('pago_fecha', Carbon::today())
            ->where('pago_estado', 2)
            ->where('pago_verificacion', 2)
            ->sum('pago_monto');
        $this->ingreso_por_dia_inscripcion = Pago::where('id_concepto_pago', 1)
            ->whereDate('pago_fecha', Carbon::today())
            ->where('pago_estado', 2)
            ->where('pago_verificacion', 2)
            ->sum('pago_monto');

        // Se calcula el ingreso por concepto de costos de enseñanza
        $costoEnsenazaMap = Mensualidad::query()
            ->with([
                'pago' => function ($query) {
                    $query->where('pago_estado', 2)
                        ->where('pago_verificacion', 2);
                },
                'matricula' => function ($query) use ($filtro_proceso_data) {
                    $query->with([
                        'admitido' => function ($query) use ($filtro_proceso_data) {
                            $query->with([
                                'programa_proceso' => function ($query) use ($filtro_proceso_data) {
                                    $query->where('id_admision', $filtro_proceso_data);
                                }
                            ]);
                        }
                    ]);
                },
            ])
            ->whereHas('pago', function ($query) {
                $query->where('pago_estado', 2)
                    ->where('pago_verificacion', 2);
            })
            ->whereHas('matricula.admitido.programa_proceso', function ($query) {
                $query->where('id_admision', $this->filtro_proceso_data);
            })
            ->get();

        $costoEnsenaza = 0;
        foreach ($costoEnsenazaMap as $item) {
            $costoEnsenaza += $item->pago->pago_monto;
        }

        $this->ingreso_costo_enseñanza = $costoEnsenaza;

        $costoEnsenazaPorDiaMap = Mensualidad::query()
            ->with([
                'pago' => function ($query) {
                    $query->where('pago_estado', 2)
                        ->where('pago_verificacion', 2)
                        ->whereDate('pago_fecha', Carbon::today());
                },
                'matricula' => function ($query) use ($filtro_proceso_data) {
                    $query->with([
                        'admitido' => function ($query) use ($filtro_proceso_data) {
                            $query->with([
                                'programa_proceso' => function ($query) use ($filtro_proceso_data) {
                                    $query->where('id_admision', $filtro_proceso_data);
                                }
                            ]);
                        }
                    ]);
                },
            ])
            ->whereHas('pago', function ($query) {
                $query->where('pago_estado', 2)
                    ->where('pago_verificacion', 2)
                    ->whereDate('pago_fecha', Carbon::today());
            })
            ->whereHas('matricula.admitido.programa_proceso', function ($query) {
                $query->where('id_admision', $this->filtro_proceso_data);
            })
            ->get();

        $costoEnsenazaPorDia = 0;
        foreach ($costoEnsenazaPorDiaMap as $item) {
            $costoEnsenazaPorDia += $item->pago->pago_monto;
        }

        $this->ingreso_por_dia_costo_enseñanza = $costoEnsenazaPorDia;

        $this->programas_maestria = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'), Inscripcion::raw('sum(case when inscripcion.inscripcion_estado = 1 then 1 else 0 end) as verificados'))
            ->where('programa.programa_estado',1)
            ->where('programa.programa_tipo',1) // 1 = Maestria
            ->where('programa_proceso.id_admision', $this->filtro_proceso_data)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->groupBy('inscripcion.id_programa_proceso')
            ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'desc')
            ->get();

        $this->programas_doctorado = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->select('programa.subprograma', 'programa.mencion', 'programa.programa', Inscripcion::raw('count(inscripcion.id_programa_proceso) as cantidad'), Inscripcion::raw('sum(case when inscripcion.inscripcion_estado = 1 then 1 else 0 end) as verificados'))
            ->where('programa.programa_estado',1)
            ->where('programa.programa_tipo',2) // 1 = Doctorado
            ->where('programa_proceso.id_admision', $this->filtro_proceso_data)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->groupBy('inscripcion.id_programa_proceso')
            ->orderBy(Inscripcion::raw('count(inscripcion.id_programa_proceso)'), 'desc')
            ->get();

        $this->matriculados_programas = Admitido::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'admitido.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->select('admitido.id_programa_proceso', 'programa.subprograma', 'programa.mencion', 'programa.programa', Admitido::raw('count(admitido.id_programa_proceso) as cantidad'),)
            ->where('programa.programa_estado',1)
            ->where('programa_proceso.id_admision', $this->filtro_proceso_data)
            ->where('admitido.admitido_estado', 1)
            ->groupBy('admitido.id_programa_proceso')
            ->orderBy(Admitido::raw('count(admitido.id_programa_proceso)'), 'desc')
            ->get();

        $this->ingreso_total = $this->ingreso_constancia + $this->ingreso_matricula + $this->ingreso_inscripcion + $this->ingreso_costo_enseñanza;
    }

    public function aplicar_filtro()
    {
        if($this->filtro_proceso == null || $this->filtro_proceso == "") {
            $this->mount();
        } else {
            $filtro = $this->filtro_proceso;
            $this->filtro_proceso_data = $this->filtro_proceso;
            $this->admision = Admision::where('id_admision', $filtro)->first();
            $this->calcularMontos();
            $this->emit('filtro_aplicado_maestria', $this->filtro_proceso_data);
        }
    }

    public function resetear_filtro()
    {
        $this->mount();
    }

    public function updatedProceso()
    {
        $this->programa = null;
    }

    public function limpiar()
    {
        $this->reset([
            'proceso',
            'programa'
        ]);
    }

    public function descargar_reporte_matriculados()
    {
        $this->validate([
            'proceso' => 'required',
            'programa' => 'required'
        ]);
        // verificamos si el programa tiene matriculados
        $matriculados = MatriculaMatricula::query()
            ->with([
                'admitido' => function ($query) {
                    $query->with([
                        'programa_proceso' => function ($query) {
                            $query->where('id_admision', $this->proceso);
                        }
                    ]);
                }
            ])
            ->whereHas('admitido.programa_proceso', function ($query) {
                $query->where('id_admision', $this->proceso);
            })
            ->get();
        if ($matriculados->count() == 0) {
            $this->dispatchBrowserEvent('alerta-usuario', [
                'title' => 'No hay matriculados',
                'text' => 'El programa seleccionado no tiene matriculados registrados',
                'icon' => 'warning',
                'confirmButtonText' => 'Aceptar',
                'color' => 'warning'
            ]);
            return;
        }
        $nombre = Str::slug('Reporte de Matriculados ' . $this->programa, '-');
        $this->dispatchBrowserEvent('modal', [
            'id' => '#modal-reporte-matriculados',
            'action' => 'hide'
        ]);
        $id_programa = $this->programa;
        $this->limpiar();
        return Excel::download(new listaGruposExport($id_programa), $nombre . '.xlsx');
    }

    public function render()
    {
        if ($this->proceso) {
            $programas = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
                ->where('programa_proceso.id_admision', $this->proceso)
                ->get();
        } else {
            $programas = collect();
        }

        return view('livewire.modulo-administrador.dashboard.index', [
            'programas' => $programas
        ]);
    }
}
