<?php

namespace App\Exports\reporte\moduloAdministrador\evaluacion;

use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class listaProgramasExport implements WithMultipleSheets
{

    use Exportable;


    public function sheets(): array
    {
        $sheets = [];

        $programas = Inscripcion::join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
            ->where('programa.programa_estado', 1)
            ->where('programa_proceso.id_admision', getAdmision()->id_admision)
            ->where('inscripcion.inscripcion_estado', 1)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.verificar_expedientes', 1)
            ->distinct()
            ->select('programa_proceso.id_programa_proceso', 'programa.programa', 'programa.subprograma', 'programa.mencion')
            ->get();

        foreach ($programas as $programa) {
            $sheets[] = new listaEvaluacionesExport($programa->id_programa_proceso);
        }

        return $sheets;
    }
}
