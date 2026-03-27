<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Acta Evaluación
    </title>
    <style>
        body {
            margin-top: 340px;
            margin-bottom: 150px;
        }

        header {
            position: fixed;
            top: -22.5px;
            width: 100%;
        }

        footer {
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: -22.5px;
            width: 100%;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @php
        $anio_cabecera = date('Y');
        $proceso_cabecera = date('n') <= 6 ? 'I' : 'II';
    @endphp

    @if ($tipo === 'regular')
        <header>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                <thead>
                    <tr>
                        <th align="left">
                            <div style="display: flex; align-items: center; margin-left: 0px;">
                                <img src="{{ public_path('assets_pdf/unu.png') }}" width="65px" height="80px" alt="logo unu">
                            </div>
                        </th>
                        <th>
                            <div style="text-align: center">
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    UNIVERSIDAD NACIONAL DE UCAYALI
                                </div>
                                <div style="margin: 0.2rem"></div>
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    ESCUELA DE POSGRADO
                                </div>
                            </div>
                        </th>
                        <th align="right">
                            <div style="display: flex; align-items: center; margin-right: 0px;">
                                <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="65px" height="80px" alt="logo posgrado">
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
            <div style="margin-top: 0.5rem; text-align: right;">
                <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                    Fecha de emisión: {{ date('d/m/Y') }}
                </span>
            </div>
            <div style="margin-top: 0.5rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    REGISTRO FINAL DE EVALUACIÓN ACADÉMICA
                </span>
            </div>
            <div style="margin-top: 0.2rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    {{ $anio_cabecera }} - {{ $proceso_cabecera }}
                </span>
            </div>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                <tbody>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                {{ $programa }}:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $subprograma }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Mención:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $mencion }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Créditos:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $creditos }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Ciclo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $ciclo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Grupo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $grupo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Tipo de Acta:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                REGULAR
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>
        <footer>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                <tbody>
                    <tr>
                        <td style="width: 33%; text-align: left; font-weight: 400; font-size: 0.65rem ">
                            Fecha de inicio del curso: ____/____/20___
                        </td>
                        <td style="width: 34%; text-align: center; font-weight: 400; font-size: 0.65rem">
                            Fecha de fin del curso: ____/____/20___
                        </td>
                        <td style="width: 33%; text-align: right; font-weight: 400; font-size: 0.65rem">
                            Fecha de entrega de acta: ____/____/20___
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 3rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    ___________________________________
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    {{ $docente }}
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    Responsable del curso
                </span>
            </div>
        </footer>
        <main>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem; border-collapse: collapse;">
                <thead>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Nro
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Código
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px; width: 190px;">
                            Alumno
                        </th>
                        <th colspan="3" style="border: 1px solid black; padding: 8px;">
                            Promedios
                        </th>
                        <th colspan="2" style="border: 1px solid black; padding: 8px;">
                            Promedio Final
                        </th>
                    </tr>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Permanente
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Medio Curso
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Final
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Número
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Letras
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriculados as $item)
                    @php
                        $item->nota_promedio_final = round($item->nota_promedio_final);
                        $letras = array('Cero', 'Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco', 'Seis', 'Siete', 'Ocho', 'Nueve', 'Diez', 'Once', 'Doce', 'Trece', 'Catorce', 'Quince', 'Dieciséis', 'Diecisiete', 'Dieciocho', 'Diecinueve', 'Veinte');
                    @endphp
                        <tr style="border: 1px solid black; padding: 4px; font-size: 0.5rem">
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->admitido_codigo }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;">
                                {{ $item->nombre_completo }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->nota_evaluacion_permanente ? round($item->nota_evaluacion_permanente) : '-' }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->nota_evaluacion_medio_curso ? round($item->nota_evaluacion_medio_curso) : '-' }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->nota_evaluacion_final ? round($item->nota_evaluacion_final) : '-' }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px; {{ $item->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}" align="center">
                                @if ($item->estado == 3)
                                    NSP
                                @else
                                    {{ round($item->nota_promedio_final) }}
                                @endif
                            </td>
                            <td style="border: 1px solid black; padding: 4px; {{ $item->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}">
                                @if ($item->estado == 3)
                                    NSP
                                @else
                                    {{ $letras[$item->nota_promedio_final] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    @endif

    @if ($tipo === 'adicional')
        <header>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                <thead>
                    <tr>
                        <th align="left">
                            <div style="display: flex; align-items: center; margin-left: 0px;">
                                <img src="{{ public_path('assets_pdf/unu.png') }}" width="65px" height="80px" alt="logo unu">
                            </div>
                        </th>
                        <th>
                            <div style="text-align: center">
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    UNIVERSIDAD NACIONAL DE UCAYALI
                                </div>
                                <div style="margin: 0.2rem"></div>
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    ESCUELA DE POSGRADO
                                </div>
                            </div>
                        </th>
                        <th align="right">
                            <div style="display: flex; align-items: center; margin-right: 0px;">
                                <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="65px" height="80px" alt="logo posgrado">
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
            <div style="margin-top: 0.5rem; text-align: right;">
                <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                    Fecha de emisión: {{ date('d/m/Y') }}
                </span>
            </div>
            <div style="margin-top: 0.5rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    REGISTRO FINAL DE EVALUACIÓN ACADÉMICA
                </span>
            </div>
            <div style="margin-top: 0.2rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    {{ $anio_cabecera }} - {{ $proceso_cabecera }}
                </span>
            </div>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                <tbody>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                {{ $programa }}:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $subprograma }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Mención:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $mencion }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Créditos:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $creditos }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Ciclo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $ciclo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Grupo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $grupo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Tipo de Acta:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                ADICIONAL
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>
        <footer>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                <tbody>
                    <tr>
                        <td style="width: 33%; text-align: left; font-weight: 400; font-size: 0.65rem ">
                            Fecha de inicio del curso: ____/____/20___
                        </td>
                        <td style="width: 34%; text-align: center; font-weight: 400; font-size: 0.65rem">
                            Fecha de fin del curso: ____/____/20___
                        </td>
                        <td style="width: 33%; text-align: right; font-weight: 400; font-size: 0.65rem">
                            Fecha de entrega de acta: ____/____/20___
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 3rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    ___________________________________
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    {{ $docente }}
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    Responsable del curso
                </span>
            </div>
        </footer>
        <main>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem; border-collapse: collapse;">
                <thead>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Nro
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Código
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px; width: 190px;">
                            Alumno
                        </th>
                        <th colspan="3" style="border: 1px solid black; padding: 8px;">
                            Promedios
                        </th>
                        <th colspan="2" style="border: 1px solid black; padding: 8px;">
                            Promedio Final
                        </th>
                    </tr>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Permanente
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Medio Curso
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Final
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Número
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Letras
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriculados_adicional as $item)
                    @php
                        $item->nota_promedio_final = round($item->nota_promedio_final);
                        $letras = array('Cero', 'Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco', 'Seis', 'Siete', 'Ocho', 'Nueve', 'Diez', 'Once', 'Doce', 'Trece', 'Catorce', 'Quince', 'Dieciséis', 'Diecisiete', 'Dieciocho', 'Diecinueve', 'Veinte');
                    @endphp
                        <tr style="border: 1px solid black; padding: 4px; font-size: 0.5rem">
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $loop->iteration }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->admitido_codigo }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;">
                                {{ $item->nombre_completo }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->nota_evaluacion_permanente ? round($item->nota_evaluacion_permanente) : '-' }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->nota_evaluacion_medio_curso ? round($item->nota_evaluacion_medio_curso) : '-' }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px;" align="center">
                                {{ $item->nota_evaluacion_final ? round($item->nota_evaluacion_final) : '-' }}
                            </td>
                            <td style="border: 1px solid black; padding: 4px; {{ $item->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}" align="center">
                                @if ($item->estado == 3)
                                    NSP
                                @else
                                    {{ round($item->nota_promedio_final) }}
                                @endif
                            </td>
                            <td style="border: 1px solid black; padding: 4px; {{ $item->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}">
                                @if ($item->estado == 3)
                                    NSP
                                @else
                                    {{ $letras[$item->nota_promedio_final] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    @endif

    @if ($tipo === 'reingreso')
        @php
            $nota_promedio_final = round($reingreso->nota_promedio_final);
            $reingresoData = App\Models\Reingreso::query()
                ->where('id_admitido', $reingreso->id_admitido)
                ->orderBy('reingreso_fecha_creacion', 'desc')
                ->first();
        @endphp
        <header>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                <thead>
                    <tr>
                        <th align="left">
                            <div style="display: flex; align-items: center; margin-left: 0px;">
                                <img src="{{ public_path('assets_pdf/unu.png') }}" width="65px" height="80px" alt="logo unu">
                            </div>
                        </th>
                        <th>
                            <div style="text-align: center">
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    UNIVERSIDAD NACIONAL DE UCAYALI
                                </div>
                                <div style="margin: 0.2rem"></div>
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    ESCUELA DE POSGRADO
                                </div>
                            </div>
                        </th>
                        <th align="right">
                            <div style="display: flex; align-items: center; margin-right: 0px;">
                                <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="65px" height="80px" alt="logo posgrado">
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
            <div style="margin-top: 0.5rem; text-align: right;">
                <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                    Fecha de emisión: {{ date('d/m/Y') }}
                </span>
            </div>
            <div style="margin-top: 0.5rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    REGISTRO FINAL DE EVALUACIÓN ACADÉMICA
                </span>
            </div>
            <div style="margin-top: 0.2rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    {{ $anio_cabecera }} - {{ $proceso_cabecera }}
                </span>
            </div>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                <tbody>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                {{ $programa }}:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $subprograma }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Mención:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $mencion }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Créditos:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $creditos }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Ciclo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $ciclo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Grupo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $grupo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Tipo de Acta:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                REINGRESO APROBADO CON RESOLUCIÓN N° {{ $reingresoData->reingreso_resolucion ?? '___-20__-UNU-CEPG-D' }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>
        <footer>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                <tbody>
                    <tr>
                        <td style="width: 33%; text-align: left; font-weight: 400; font-size: 0.65rem ">
                            Fecha de inicio del curso: ____/____/20___
                        </td>
                        <td style="width: 34%; text-align: center; font-weight: 400; font-size: 0.65rem">
                            Fecha de fin del curso: ____/____/20___
                        </td>
                        <td style="width: 33%; text-align: right; font-weight: 400; font-size: 0.65rem">
                            Fecha de entrega de acta: ____/____/20___
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 3rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    ___________________________________
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    {{ $docente }}
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    Responsable del curso
                </span>
            </div>
        </footer>
        <main>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem; border-collapse: collapse;">
                <thead>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Nro
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Código
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px; width: 190px;">
                            Alumno
                        </th>
                        <th colspan="3" style="border: 1px solid black; padding: 8px;">
                            Promedios
                        </th>
                        <th colspan="2" style="border: 1px solid black; padding: 8px;">
                            Promedio Final
                        </th>
                    </tr>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Permanente
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Medio Curso
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Final
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Número
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Letras
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $letras = array('Cero', 'Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco', 'Seis', 'Siete', 'Ocho', 'Nueve', 'Diez', 'Once', 'Doce', 'Trece', 'Catorce', 'Quince', 'Dieciséis', 'Diecisiete', 'Dieciocho', 'Diecinueve', 'Veinte');
                    @endphp
                    <tr style="border: 1px solid black; padding: 4px; font-size: 0.5rem">
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            1
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $reingreso->admitido_codigo }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;">
                            {{ $reingreso->nombre_completo }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $reingreso->nota_evaluacion_permanente ? round($reingreso->nota_evaluacion_permanente) : '-' }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $reingreso->nota_evaluacion_medio_curso ? round($reingreso->nota_evaluacion_medio_curso) : '-' }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $reingreso->nota_evaluacion_final ? round($reingreso->nota_evaluacion_final) : '-' }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px; {{ $reingreso->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}" align="center">
                            @if ($reingreso->estado == 3)
                                NSP
                            @else
                                {{ round($reingreso->nota_promedio_final) }}
                            @endif
                        </td>
                        <td style="border: 1px solid black; padding: 4px; {{ $reingreso->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}">
                            @if ($reingreso->estado == 3)
                                NSP
                            @else
                                {{ $letras[$nota_promedio_final] }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    @endif

    @if ($tipo === 'incorporacion')
        @php
            $incorporacion->nota_promedio_final = round($incorporacion->nota_promedio_final);
            $reincorporacion = App\Models\Reincorporacion::query()
                ->where('id_admitido', $incorporacion->id_admitido)
                ->orderBy('created_at', 'desc')
                ->first();
        @endphp
        <header>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                <thead>
                    <tr>
                        <th align="left">
                            <div style="display: flex; align-items: center; margin-left: 0px;">
                                <img src="{{ public_path('assets_pdf/unu.png') }}" width="65px" height="80px" alt="logo unu">
                            </div>
                        </th>
                        <th>
                            <div style="text-align: center">
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    UNIVERSIDAD NACIONAL DE UCAYALI
                                </div>
                                <div style="margin: 0.2rem"></div>
                                <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                    ESCUELA DE POSGRADO
                                </div>
                            </div>
                        </th>
                        <th align="right">
                            <div style="display: flex; align-items: center; margin-right: 0px;">
                                <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="65px" height="80px" alt="logo posgrado">
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
            <div style="margin-top: 0.5rem; text-align: right;">
                <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                    Fecha de emisión: {{ date('d/m/Y') }}
                </span>
            </div>
            <div style="margin-top: 0.5rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    REGISTRO FINAL DE EVALUACIÓN ACADÉMICA
                </span>
            </div>
            <div style="margin-top: 0.2rem; text-align: center;">
                <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                    {{ $anio_cabecera }} - {{ $proceso_cabecera }}
                </span>
            </div>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                <tbody>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                {{ $programa }}:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $subprograma }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Mención:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $mencion }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Curso:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_curso }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Cod. Docente:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $codigo_docente }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Créditos:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $creditos }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Ciclo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $ciclo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Grupo:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $grupo }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Tipo de Acta:
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                INCORPORACION APROBADO CON RESOLUCIÓN N° {{ $reincorporacion->reincorporacion_resolucion ?? '___-20__-UNU-CEPG-D' }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>
        <footer>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                <tbody>
                    <tr>
                        <td style="width: 33%; text-align: left; font-weight: 400; font-size: 0.65rem ">
                            Fecha de inicio del curso: ____/____/20___
                        </td>
                        <td style="width: 34%; text-align: center; font-weight: 400; font-size: 0.65rem">
                            Fecha de fin del curso: ____/____/20___
                        </td>
                        <td style="width: 33%; text-align: right; font-weight: 400; font-size: 0.65rem">
                            Fecha de entrega de acta: ____/____/20___
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 3rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    ___________________________________
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    {{ $docente }}
                </span>
            </div>
            <div style="margin-top: 0rem; text-align: right;">
                <span style="font-weight: 400; font-size: 0.7rem">
                    Responsable del curso
                </span>
            </div>
        </footer>
        <main>
            <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem; border-collapse: collapse;">
                <thead>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Nro
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                            Código
                        </th>
                        <th rowspan="2" style="border: 1px solid black; padding: 8px; width: 190px;">
                            Alumno
                        </th>
                        <th colspan="3" style="border: 1px solid black; padding: 8px;">
                            Promedios
                        </th>
                        <th colspan="2" style="border: 1px solid black; padding: 8px;">
                            Promedio Final
                        </th>
                    </tr>
                    <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Permanente
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Medio Curso
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Evaluación<br>Final
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Número
                        </th>
                        <th style="border: 1px solid black; padding: 8px;">
                            Letras
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $letras = array('Cero', 'Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco', 'Seis', 'Siete', 'Ocho', 'Nueve', 'Diez', 'Once', 'Doce', 'Trece', 'Catorce', 'Quince', 'Dieciséis', 'Diecisiete', 'Dieciocho', 'Diecinueve', 'Veinte');
                    @endphp
                    <tr style="border: 1px solid black; padding: 4px; font-size: 0.5rem">
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            1
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $incorporacion->admitido_codigo }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;">
                            {{ $incorporacion->nombre_completo }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $incorporacion->nota_evaluacion_permanente ? round($incorporacion->nota_evaluacion_permanente) : '-' }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $incorporacion->nota_evaluacion_medio_curso ? round($incorporacion->nota_evaluacion_medio_curso) : '-' }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px;" align="center">
                            {{ $incorporacion->nota_evaluacion_final ? round($incorporacion->nota_evaluacion_final) : '-' }}
                        </td>
                        <td style="border: 1px solid black; padding: 4px; {{ $incorporacion->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}" align="center">
                            @if ($incorporacion->estado == 3)
                                NSP
                            @else
                                {{ round($incorporacion->nota_promedio_final) }}
                            @endif
                        </td>
                        <td style="border: 1px solid black; padding: 4px; {{ $incorporacion->nota_promedio_final < 14 ? 'color: #ff0000;' : '' }}">
                            @if ($incorporacion->estado == 3)
                                NSP
                            @else
                                {{ $letras[$incorporacion->nota_promedio_final] }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    @endif
</body>
</html>
