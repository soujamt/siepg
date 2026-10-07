<?php

namespace App\Exports\reporte\moduloAdministrador\inscripcion;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class listaInscripcionesExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    public $inscripciones;
    public $contador = 1;

    // recibe las inscripciones ya filtradas desde Gestión de Admisión > Inscripciones
    public function __construct($inscripciones)
    {
        $this->inscripciones = $inscripciones;
    }

    public function collection()
    {
        return $this->inscripciones;
    }

    public function map($inscripcion): array
    {
        $estados = [0 => 'PENDIENTE', 1 => 'VERIFICADO', 2 => 'OBSERVADO'];

        return [
            $this->contador++,
            $inscripcion->inscripcion_codigo,
            formatearAdmisionVisual($inscripcion->admision),
            $inscripcion->apellido_paterno,
            $inscripcion->apellido_materno,
            $inscripcion->nombre,
            $inscripcion->numero_documento,
            $inscripcion->celular,
            $inscripcion->correo,
            $inscripcion->programa . ' EN ' . $inscripcion->subprograma . ($inscripcion->mencion ? ' CON MENCION EN ' . $inscripcion->mencion : ''),
            $inscripcion->modalidad,
            $inscripcion->es_traslado_externo == 1 ? 'TRASLADO EXTERNO' : 'REGULAR',
            date('d/m/Y', strtotime($inscripcion->inscripcion_fecha)),
            $estados[$inscripcion->inscripcion_estado] ?? '-',
            $estados[$inscripcion->verificar_expedientes] ?? '-',
            $inscripcion->retiro_inscripcion == 1 ? 'SI' : 'NO',
        ];
    }

    public function headings(): array
    {
        return ['N°', 'Código', 'Proceso', 'Apellido Paterno', 'Apellido Materno', 'Nombres', 'Documento', 'Celular', 'Correo', 'Programa', 'Modalidad', 'Tipo', 'Fecha', 'Estado Inscripción', 'Expedientes', 'Reservada'];
    }

    //agregar estilos a las celdas
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $anchos = ['A' => 6, 'B' => 14, 'C' => 20, 'D' => 22, 'E' => 22, 'F' => 30, 'G' => 14, 'H' => 14, 'I' => 35, 'J' => 80, 'K' => 14, 'L' => 18, 'M' => 12, 'N' => 18, 'O' => 14, 'P' => 11];
                foreach ($anchos as $columna => $ancho) {
                    $event->sheet->getColumnDimension($columna)->setWidth($ancho);
                }
                $event->sheet->getStyle('A1:P1')->getFont()->setBold(true);
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
