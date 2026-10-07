<?php

namespace App\Exports\reporte\moduloAdministrador\matriculados;

use App\Models\Evaluacion;
use App\Models\Inscripcion;
use App\Models\Matricula;
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

class listaMatriculadosExport implements FromCollection, WithMapping, ShouldAutoSize, WithTitle, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $item = 0;
    protected $programa;
    protected $id_programa_proceso_grupo;
    protected $nombre_grupo;
    protected $tipo_programa;
    protected $programa_nombre;
    protected $programa_nombre_corto;

    public function __construct($programa, $nombre_grupo, $id_programa_proceso_grupo)
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
        $this->id_programa_proceso_grupo = $id_programa_proceso_grupo;
        $this->nombre_grupo = $nombre_grupo;
    }

    public function collection()
    {
        $programas = Matricula::join('admitido','matricula.id_admitido','=','admitido.id_admitido')
            ->join('programa_proceso','admitido.id_programa_proceso','=','programa_proceso.id_programa_proceso')
            ->join('programa_plan','programa_proceso.id_programa_plan','=','programa_plan.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->join('persona','admitido.id_persona','=','persona.id_persona')
            ->where('programa.programa_estado',1)
            ->where('admitido.id_programa_proceso',$this->programa->id_programa_proceso)
            ->where('matricula.id_programa_proceso_grupo',$this->id_programa_proceso_grupo)
            ->where('matricula.matricula_estado',1)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        return $programas;
    }

    public function map($programas): array
    {
        if ($programas->mencion) {
            $programa = $programas->programa . ' EN ' . $programas->subprograma . ' CON MENCION EN ' . $programas->mencion;
        } else {
            $programa = $programas->programa . ' EN ' . $programas->subprograma;
        }

        return [
            $this->item++,
            $programas->numero_documento,
            $programas->apellido_paterno . ' ' . $programas->apellido_materno,
            $programas->nombre,
            $programas->celular,
            $programas->correo,
            $programa
        ];
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

                $event->sheet->setCellValue('A1', 'LISTADO DE MATRICULADOS - ' . $this->programa_nombre . ' - GRUPO ' . $this->nombre_grupo);

                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray($tamanio);
                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray($negrita);
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray($centrar);

                $columnas = ['N°', 'DNI', 'APELLIDOS', 'NOMBRES', 'CELULAR', 'CORREO', 'PROGRAMA ACADEMICO'];
                $event->sheet->getDelegate()->fromArray($columnas, NULL, 'A3');

                $event->sheet->getDelegate()->getStyle('A3:G3')->applyFromArray($header);
                $event->sheet->getDelegate()->getStyle('A3:G3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99a3a4');
                for ($i = 1; $i <= $this->item; $i++) {
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2))->applyFromArray($negrita);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':G' . ($i + 2))->applyFromArray($border);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':B' . ($i + 2))->applyFromArray($centrar);
                    $event->sheet->getDelegate()->getStyle('E' . ($i + 2) . ':E' . ($i + 2))->applyFromArray($centrar);
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
        return 'Grupo' . $this->nombre_grupo;
    }
}