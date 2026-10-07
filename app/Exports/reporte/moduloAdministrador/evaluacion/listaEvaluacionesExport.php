<?php

namespace App\Exports\reporte\moduloAdministrador\evaluacion;

use App\Models\Evaluacion;
use App\Models\Inscripcion;
use App\Models\ProgramaProceso;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Str;

class listaEvaluacionesExport implements FromCollection, WithMapping, ShouldAutoSize, WithTitle, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $item = 0;
    protected $programa;
    protected $tipo_programa;
    protected $programa_nombre;
    protected $programa_nombre_corto;

    public function __construct($programa)
    {
        $this->item = 1;
        $this->programa = ProgramaProceso::find($programa);
        if ($this->programa->programa_plan->programa->mencion) {
            $this->programa_nombre = $this->programa->programa_plan->programa->programa . ' EN ' . $this->programa->programa_plan->programa->subprograma . ' CON MENCION EN ' . $this->programa->programa_plan->programa->mencion;
            $this->programa_nombre_corto = 'MENCION EN ' . $this->programa->programa_plan->programa->mencion;
            $this->programa_nombre_corto = Str::slug($this->programa_nombre_corto, ' ');
        } else {
            $this->programa_nombre = $this->programa->programa_plan->programa->programa . ' EN ' . $this->programa->programa_plan->programa->subprograma;
            $this->programa_nombre_corto = $this->programa->programa_plan->programa->programa . ' EN ' . $this->programa->programa_plan->programa->subprograma;
            $this->programa_nombre_corto = Str::slug($this->programa_nombre_corto, ' ');
        }
        $this->tipo_programa = $this->programa->programa_plan->programa->programa_tipo;
    }

    public function collection()
    {
        $programas = Inscripcion::join('programa_proceso','inscripcion.id_programa_proceso','=','programa_proceso.id_programa_proceso')
            ->join('programa_plan','programa_proceso.id_programa_plan','=','programa_plan.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->join('persona','inscripcion.id_persona','=','persona.id_persona')
            ->where('programa.programa_estado',1)
            ->where('inscripcion.inscripcion_estado',1)
            ->where('inscripcion.retiro_inscripcion',0)
            ->where('inscripcion.verificar_expedientes',1)
            ->where('inscripcion.id_programa_proceso',$this->programa->id_programa_proceso)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        return $programas;
    }

    public function map($inscritos): array
    {
        if ($inscritos->mencion) {
            $programa = $inscritos->programa . ' EN ' . $inscritos->subprograma . ' CON MENCION EN ' . $inscritos->mencion;
        } else {
            $programa = $inscritos->programa . ' EN ' . $inscritos->subprograma;
        }
        $evaluacion = Evaluacion::where('id_inscripcion', $inscritos->id_inscripcion)->first();
        $puntaje_expediente = $evaluacion ? ( $evaluacion->puntaje_expediente ? $evaluacion->puntaje_expediente : '-' ) : '-';
        $puntaje_investigacion = $evaluacion ? ( $evaluacion->puntaje_investigacion ? $evaluacion->puntaje_investigacion : '-' ) : '-';
        $puntaje_entrevista = $evaluacion ? ( $evaluacion->puntaje_entrevista ? $evaluacion->puntaje_entrevista : '-' ) : '-';
        $puntaje_final = $evaluacion ? ( $evaluacion->puntaje_final ? $evaluacion->puntaje_final : '-' ) : '-';

        if ($this->tipo_programa == 1) {
            return [
                $this->item++,
                $inscritos->numero_documento,
                $inscritos->apellido_paterno . ' ' . $inscritos->apellido_materno,
                $inscritos->nombre,
                $inscritos->celular,
                $inscritos->correo,
                $puntaje_expediente,
                $puntaje_entrevista,
                $puntaje_final,
                $programa
            ];
        } else {
            return [
                $this->item++,
                $inscritos->numero_documento,
                $inscritos->apellido_paterno . ' ' . $inscritos->apellido_materno,
                $inscritos->nombre,
                $inscritos->celular,
                $inscritos->correo,
                $puntaje_expediente,
                $puntaje_investigacion,
                $puntaje_entrevista,
                $puntaje_final,
                $programa
            ];
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Listado de Evaluaciones - Escuela de Posgrado');
                $event->writer->getProperties()->setTitle('Listado de Evaluaciones - Escuela de Posgrado');

            },
            AfterSheet::class => function (AfterSheet $event) {

                $negrita = [
                    'font' => [
                        'bold' => true,
                    ],
                ];
                $header = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ];
                $centrar = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ];
                $italic = [
                    'font' => [
                        'italic' => true,
                    ]
                ];
                $tamanio = [
                    'font' => [
                        'size' => 14,
                    ]
                ];
                $border = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

                $event->sheet->setCellValue('A1', 'LISTADO DE INSCRITOS PARA LAS EVALUACIONES - ' . $this->programa_nombre);

                if ($this->tipo_programa == 1) {
                    $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray($tamanio);
                    $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray($negrita);
                    $event->sheet->getDelegate()->mergeCells('A1:J1');
                    $event->sheet->getDelegate()->getStyle('A1:J1')->applyFromArray($centrar);

                    $columnas = ['N°', 'DNI', 'APELLIDOS', 'NOMBRES', 'CELULAR', 'CORREO', 'PUNTAJE EXPEDIENTE', 'PUNTAJE ENTREVISTA', 'PUNTAJE FINAL', 'PROGRAMA ACADEMICO'];
                    $event->sheet->getDelegate()->fromArray($columnas, NULL, 'A3');

                    $event->sheet->getDelegate()->getStyle('A3:J3')->applyFromArray($header);
                    $event->sheet->getDelegate()->getStyle('A3:J3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99a3a4');
                    for ($i = 1; $i <= $this->item; $i++) {
                        $event->sheet->getDelegate()->getStyle('A' . ($i + 2))->applyFromArray($negrita);
                        $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':J' . ($i + 2))->applyFromArray($border);
                        $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':B' . ($i + 2))->applyFromArray($centrar);
                        $event->sheet->getDelegate()->getStyle('E' . ($i + 2) . ':E' . ($i + 2))->applyFromArray($centrar);
                        $event->sheet->getDelegate()->getStyle('G' . ($i + 2) . ':I' . ($i + 2))->applyFromArray($centrar);
                    }
                } else {
                    $event->sheet->getDelegate()->getStyle('A1:K1')->applyFromArray($tamanio);
                    $event->sheet->getDelegate()->getStyle('A1:K1')->applyFromArray($negrita);
                    $event->sheet->getDelegate()->mergeCells('A1:K1');
                    $event->sheet->getDelegate()->getStyle('A1:K1')->applyFromArray($centrar);

                    $columnas = ['N°', 'DNI', 'APELLIDOS', 'NOMBRES', 'CELULAR', 'CORREO', 'PUNTAJE EXPEDIENTE', 'PUNTAJE INVESTIGACION', 'PUNTAJE ENTREVISTA', 'PUNTAJE FINAL', 'PROGRAMA ACADEMICO'];
                    $event->sheet->getDelegate()->fromArray($columnas, NULL, 'A3');

                    $event->sheet->getDelegate()->getStyle('A3:K3')->applyFromArray($header);
                    $event->sheet->getDelegate()->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99a3a4');
                    for ($i = 1; $i <= $this->item; $i++) {
                        $event->sheet->getDelegate()->getStyle('A' . ($i + 2))->applyFromArray($negrita);
                        $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':K' . ($i + 2))->applyFromArray($border);
                        $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':B' . ($i + 2))->applyFromArray($centrar);
                        $event->sheet->getDelegate()->getStyle('E' . ($i + 2) . ':E' . ($i + 2))->applyFromArray($centrar);
                        $event->sheet->getDelegate()->getStyle('G' . ($i + 2) . ':J' . ($i + 2))->applyFromArray($centrar);
                    }
                }

            },
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function title(): string
    {
        return $this->programa_nombre_corto;
    }
}
