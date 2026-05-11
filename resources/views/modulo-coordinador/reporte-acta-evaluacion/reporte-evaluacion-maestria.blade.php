<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Evaluacion Maestria</title>
    <link rel="shortcut icon" href="{{ public_path('assets/media/logos/logo-pg.png') }}"/>
    <style>
        @page {
            margin: 1.2rem 0 1.2rem 0;
        }

        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            border: 0;
        }

        body {
            padding: 1rem 0 1rem 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .content-block {
            padding-right: 4rem;
            padding-left: 4rem;
            font-size: 0.9rem;
            text-align: justify;
        }

        .content-block.top-md {
            padding-top: 1rem;
        }

        .content-block.top-lg {
            padding-top: 1.5rem;
            line-height: 1.5;
        }

        .customTable thead {
            display: table-header-group;
            background-color: #A8D08D;
        }

        .customTable tr {
            page-break-inside: avoid;
        }

        .closing-section {
            page-break-inside: avoid;
            margin-top: 1rem;
        }

        .signature-block {
            padding-top: 2rem;
            font-size: 0.9rem;
            text-align: justify;
        }

        .signature-block.compact {
            padding-top: 1.5rem;
        }

        .signature-block.spacious {
            padding-top: 4rem;
        }

        .signature-table {
            width: 100%;
        }

        .signature-table + .signature-table {
            margin-top: 2rem;
        }

        table.customTable {
            width: 100%;
            background-color: #FFFFFF;
            border-collapse: collapse;
            border-width: 1px;
            border-color: #00000078;
            border-style: solid;
            color: #000000;
        }

        table.customTable td,
        table.customTable th {
            border-width: 1px;
            border-color: #00000078;
            border-style: solid;
            padding: 3px;
        }
    </style>
</head>

<body>
    @php
        $totalPostulantes = $evaluaciones->count() + $evaluaciones_trasalados_externos->count();
        $closingSectionClass = 'content-block closing-section';
        $signatureBlockClass = 'signature-block';

        if ($totalPostulantes <= 10) {
            $signatureBlockClass .= ' spacious';
        } elseif ($totalPostulantes >= 24) {
            $signatureBlockClass .= ' compact';
        }
    @endphp

    <table class="table" style="width:100%; padding-right: 1rem; padding-left: 1rem; padding-bottom: 1.5rem; padding-top: 1.5rem;">
        <thead>
            <tr>
                <th>
                    <div style="display: flex; align-items: center; margin-left: 35px;">
                        <img src="{{ public_path('assets_pdf/unu.png') }}" width="80px" height="100px" alt="logo unu">
                    </div>
                </th>
                <th>
                    <div style="text-align: center">
                        <div class="" style="font-weight: 700; font-size: 1.5rem;">
                            UNIVERSIDAD NACIONAL DE UCAYALI
                        </div>
                        <div style="margin: 0.2rem"></div>
                        <div class="" style="font-weight: 700; font-size: 1.6rem;">
                            Escuela de Posgrado
                        </div>
                    </div>
                </th>
                <th>
                    <div style="display: flex; align-items: center; margin-right: 35px;">
                        <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="80px" height="100px" alt="logo posgrado">
                    </div>
                </th>
            </tr>
        </thead>
    </table>
    <div>
        <div style="margin: auto; width: 80%;">
            <div style="margin-top: 0.5rem;">
                <div>
                    <h4 style="text-align: center; font-weight: 700; text-decoration-line: underline">ACTA DE EVALUACIÓN DE POSTULANTES A MAESTRIA</h4>
                </div>
            </div>
        </div>
    </div>
    <div style="padding-right: 4rem; padding-left: 4rem; padding-top: 1.5rem; font-size: 0.9rem; text-align: justify; line-height: 1.5;">
        En Pucallpa a los días ..... días del mes de .................. del 2026, se reunieron en los ambientes de la Escuela de Posgrado de la Universidad Nacional de Ucayali, en conformidad con la Resolución N° 004-2026-UNU-CEPG-D, que aprueba el Cronograma del Concurso de Admisión 2026-I de la Escuela de Posgrado, y sus ampliaciones mediante las Resoluciones N.° 110-2026-UNU-CEPG-D y N.° 0355-2026-UNU-CEPG-D, la Comisión de Evaluación de Postulantes de la
        <strong>Maestria
            @if ($mencion == null)
                en {{ $maestria }}
            @else
                en {{ $maestria }} con Mención en {{ $mencion }}
            @endif
        </strong> en
        <strong>
            Modalidad {{ $modalidad }}
        </strong> integrada por los siguientes docentes:
    </div>
    <div style="padding-right: 7rem; padding-left: 7rem; padding-top: 0.5rem; font-size: 0.9rem; text-align: justify; line-height: 1.5;">
        <table>
            <tbody>
                <tr>
                    <td><strong>Presidente</strong></td>
                    <td><strong>:</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Secretario</strong></td>
                    <td><strong>:</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Vocal</strong></td>
                    <td><strong>:</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="padding-right: 6rem; padding-left: 6rem; padding-top: 1.5rem; font-size: 0.9rem; text-align: justify; line-height: 1.5;">
        Con	la	finalidad	de	evaluar	a	los	postulantes	de	la Maestría y después de realizado el Proceso de Evaluación, la relación de postulantes es como sigue:
    </div>
    <div style="padding-right: 4rem; padding-left: 4rem; padding-top: 1rem; font-size: 0.9rem; text-align: justify;">
        <table class="customTable">
            <thead>
                <tr style="font-size: 0.7rem; font-weight: 700;">
                    <th rowspan="2">N°</th>
                    <th rowspan="2">APELLIDOS Y NOMBRES</th>
                    <th colspan="2">EVALUACION (PUNTOS)</th>
                    <th rowspan="2">PUNTAJE <br> TOTAL</th>
                    <th rowspan="2">RESULTADO</th>
                    <th rowspan="2">OBSERVACION</th>
                </tr>
                <tr style="font-size: 0.7rem; font-weight: 700;">
                    <th>EXPEDIENTE <br> C. VITAE (20)</th>
                    <th>ENTREVISTA <br> PERSONAL (30)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evaluaciones as $item)
                    <tr style="font-size: 0.55rem">
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nombre_completo }}</td>
                        <td align="center">{{ number_format($item->puntaje_expediente,0) }}</td>
                        <td align="center">{{ number_format($item->puntaje_entrevista,0) }}</td>
                        <td align="center">{{ number_format($item->puntaje_final,0) }}</td>
                        @if ($item->evaluacion_estado == 2)
                            <td align="center">ADMITIDO</td>
                        @elseif ($item->evaluacion_estado == 3)
                            <td align="center">NO ADMITIDO</td>
                        @else
                            <td align="center">POR EVALUAR</td>
                        @endif
                        <td>{{ $item->evaluacion_observacion ? $item->evaluacion_observacion : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($evaluaciones_trasalados_externos->count() > 0 )
        <div style="padding-right: 4rem; padding-left: 4rem; padding-top: 1.5rem; font-size: 0.9rem; text-align: justify; line-height: 1.5;">
            A continuación, se presenta el cuadro de resultados de la evaluación de las inscripciones con Modalidad de <strong>Traslado Externo</strong>:
        </div>
        <div style="padding-right: 4rem; padding-left: 4rem; padding-top: 1rem; font-size: 0.9rem; text-align: justify;">
            <table class="customTable">
                <thead>
                    <tr style="font-size: 0.7rem; font-weight: 700;">
                        <th rowspan="2">N°</th>
                        <th rowspan="2">APELLIDOS Y NOMBRES</th>
                        <th colspan="2">EVALUACION (PUNTOS)</th>
                        <th rowspan="2">PUNTAJE <br> TOTAL</th>
                        <th rowspan="2">RESULTADO</th>
                        <th rowspan="2">OBSERVACION</th>
                    </tr>
                    <tr style="font-size: 0.7rem; font-weight: 700;">
                        <th>EXPEDIENTE <br> C. VITAE (20)</th>
                        <th>ENTREVISTA <br> PERSONAL (30)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluaciones_trasalados_externos as $item)
                        <tr style="font-size: 0.55rem">
                            <td align="center">{{ $loop->iteration }}</td>
                            <td>{{ $item->nombre_completo }}</td>
                            <td align="center">{{ number_format($item->puntaje_expediente,0) }}</td>
                            <td align="center">{{ number_format($item->puntaje_entrevista,0) }}</td>
                            <td align="center">{{ number_format($item->puntaje_final,0) }}</td>
                            @if ($item->evaluacion_estado == 2)
                                <td align="center">ADMITIDO</td>
                            @elseif ($item->evaluacion_estado == 3)
                                <td align="center">NO ADMITIDO</td>
                            @else
                                <td align="center">POR EVALUAR</td>
                            @endif
                            <td>{{ $item->evaluacion_observacion ? $item->evaluacion_observacion : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <div class="{{ $closingSectionClass }}">
        <div style="line-height: 1.5;">
            Terminado el acto de evaluación, a los ..... días del mes de .................. del 202...., se hace llegar los resultados a la Dirección de la Escuela de Posgrado de la UNU y se procede a firmar el acta en señal de conformidad.
        </div>
        <div class="{{ $signatureBlockClass }}">
            <table class="signature-table">
            <tbody>
                <tr>
                    <td align="center"><strong>...........................................</strong></td>
                    <td align="center"><strong>...........................................</strong></td>
                </tr>
                <tr>
                    <td align="center"><strong>PRESIDENTE</strong></td>
                    <td align="center"><strong>SECRETARIO</strong></td>
                </tr>
            </tbody>
            </table>
            <table class="signature-table">
                <tbody>
                    <tr>
                        <td align="center"><strong>...........................................</strong></td>
                    </tr>
                    <tr>
                        <td align="center"><strong>VOCAL</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
