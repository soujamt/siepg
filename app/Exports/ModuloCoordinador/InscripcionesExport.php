<?php

namespace App\Exports\ModuloCoordinador;

use App\Models\Inscripcion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class InscripcionesExport implements FromCollection, WithHeadings, WithEvents, WithMapping
{
    public $id_programa;
    public $id_admision;
    public $contador = 1;

    public function __construct($id_programa, $id_admision)
    {
        $this->id_programa = $id_programa;
        $this->id_admision = $id_admision;
        $this->contador = 1;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Inscripcion::query()
            ->select('inscripcion.id_inscripcion', 'persona.apellido_paterno', 'persona.apellido_materno', 'persona.nombre', 'persona.nombre_completo', 'persona.numero_documento', 'persona.celular', 'persona.celular_opcional', 'persona.correo', 'persona.correo_opcional', 'persona.especialidad_carrera')
            ->join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
            ->join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
            ->where('programa_proceso.id_admision', $this->id_admision)
            ->where('programa.id_programa', $this->id_programa)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();
    }

    public function map($inscripcion): array
    {
        return [
            $this->contador++,
            $this->limpiarEspaciosDeMasNombres($inscripcion->apellido_paterno),
            $this->limpiarEspaciosDeMasNombres($inscripcion->apellido_materno),
            $this->limpiarEspaciosDeMasNombres($inscripcion->nombre),
            $inscripcion->numero_documento,
            $inscripcion->celular,
            $inscripcion->celular_opcional,
            $inscripcion->correo,
            $inscripcion->correo_opcional,
            $inscripcion->especialidad_carrera
        ];
    }

    public function headings(): array
    {
        return ["ID", "Apellidos Paterno", "Apellido Materno", "Nombres", "Documento", "Celular", "Celular Opcional", "Correo Electronico", "Correo Electronico Opcional", "Especialidad"];
    }

    //agregar estilos a las celdas
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // $event->sheet->autoSize();
                $event->sheet->getColumnDimension('A')->setWidth(10);
                $event->sheet->getColumnDimension('B')->setWidth(30);
                $event->sheet->getColumnDimension('C')->setWidth(30);
                $event->sheet->getColumnDimension('D')->setWidth(40);
                $event->sheet->getColumnDimension('E')->setWidth(30);
                $event->sheet->getColumnDimension('F')->setWidth(30);
                $event->sheet->getColumnDimension('G')->setWidth(30);
                $event->sheet->getColumnDimension('H')->setWidth(40);
                $event->sheet->getColumnDimension('I')->setWidth(40);
                $event->sheet->getColumnDimension('J')->setWidth(60);
                $event->sheet->getStyle('A1:J1')->getFont()->setBold(true);
                $event->sheet->getStyle('A1:J1')->getFont()->setSize(11);
                $event->sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('9dceff');
                $event->sheet->getStyle('A1:J1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                $event->sheet->getStyle('A1:J1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->setAutoFilter('A1:J1');
                $event->sheet->getStyle('A1:J1')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('A1:J1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                // agregar estilo al resto de las celdas
                $event->sheet->getStyle('A2:J'.$event->sheet->getHighestRow())->applyFromArray($styleArray);
                $event->sheet->getStyle('A2:J'.$event->sheet->getHighestRow())->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('A2:J'.$event->sheet->getHighestRow())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // alinear texto a la izquierda
                $event->sheet->getStyle('A2:A'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('B2:B'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('B2:C'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('B2:D'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('C2:E'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('D2:F'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('E2:G'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('F2:H'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('G2:I'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('H2:J'.$event->sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            },
        ];
    }

    public function limpiarEspaciosDeMasNombres($texto)
    {
        $texto = trim($texto); // Eliminar espacios al inicio y al final
        $texto = preg_replace('/\s+/', ' ', $texto); // Reemplazar múltiples espacios por uno solo
        return $texto;
    }
}