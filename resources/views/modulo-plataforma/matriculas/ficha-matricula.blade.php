<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ficha de Matricula - {{ $nombre }}</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            border: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <table class="table"
        style="width:100%; padding-right: 3.5rem; padding-left: 3.5rem; padding-bottom: 1rem; padding-top: 3.5rem;">
        <thead>
            <tr>
                <th>
                    <div style="display: flex; align-items: center; margin-left: 35px;">
                        <img src="{{ public_path('assets_pdf/unu.png') }}" width="80px" height="90px"
                            alt="logo unu">
                    </div>
                </th>
                <th>
                    <div style="text-align: center">
                        <div class="" style="font-weight: 700; font-size: 1.3rem;">
                            UNIVERSIDAD NACIONAL DE UCAYALI
                        </div>
                        <div style="margin: 0.2rem"></div>
                        <div class="" style="font-weight: 700; font-size: 1.3rem;">
                            ESCUELA DE POSGRADO
                        </div>
                    </div>
                </th>
                <th>
                    <div style="display: flex; align-items: center; margin-right: 35px;">
                        <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="80px" height="90px"
                            alt="logo posgrado">
                    </div>
                </th>
            </tr>
        </thead>
    </table>

    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.2rem; font-weight: bold; font-size: 1rem;" width="100%">
        <tbody>
            <tr>
                <td align="left">
                    {{ $programa }}: <span style="font-weight: regular;">{{ $subprograma }}</span>
                </td>
                <td align="center" style="font-size: 0.9rem; border: 1px solid black; padding: 0.2rem 0rem;">
                    PLAN DE ESTUDIOS
                </td>
            </tr>
        </tbody>
    </table>

    @if ($mencion != null)
    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.9rem; font-weight: bold; font-size: 1rem;">
        <tbody>
            <tr>
                <td align="left">
                    MENCION: <span style="font-weight: regular;">{{ $mencion }}</span>
                </td>
            </tr>
        </tbody>
    </table>
    @endif

    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.9rem; font-weight: bold; font-size: 1rem;">
        <tbody>
            <tr>
                <td align="left">
                    MODALIDAD: <span style="font-weight: regular;">{{ $modalidad }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.9rem; font-weight: bold; font-size: 1rem;">
        <tbody>
            <tr>
                <td align="left">
                    GRUPO: <span style="font-weight: regular;">{{ $grupo }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.9rem; font-weight: bold; font-size: 1rem;">
        <tbody>
            <tr>
                <td align="left">
                    FICHA DE MATRICULA
                </td>
            </tr>
        </tbody>
    </table>

    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.9rem; font-weight: bold; font-size: 1rem;" width="100%">
        <tbody>
            <tr>
                <td align="left">
                    FECHA: <span style="font-weight: regular;">{{ $fecha }}</span>
                </td>
                <td align="center">
                    RECIBO: <span style="font-weight: regular;">{{ $numero_operacion }}</span>
                </td>
                <td align="right">
                    PLAN VIGENTE: <span style="font-weight: regular;">{{ $plan }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.9rem; font-weight: bold; font-size: 1rem;" width="100%">
        <tbody>
            <tr>
                <td align="left">
                    PROCESO: <span style="font-weight: regular;">{{ formatearAdmisionVisual($admision) }}</span>
                </td>
                <td align="center">
                    {{-- CICLO: <span style="font-weight: regular;">{{ $ciclo }}</span> --}}
                </td>
                <td align="right">
                    {{-- CICLO: <span style="font-weight: regular;">{{ $ciclo }}</span> --}}
                </td>
            </tr>
        </tbody>
    </table>

    <table style="padding-right: 6.5rem; padding-left: 6.5rem; padding-top: 0.9rem; font-size: 0.9rem; width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="font-weight: bold;">
                <th style="border: 1px solid; padding: 0.3rem; width: 15%"><em>Código de Alumno</em></th>
                <th style="border: 1px solid; padding: 0.3rem; width: 85%" colspan="3"><em>Apellidos y Nombres</em></th>
                {{-- <th style="border: 1px solid; padding: 0.3rem; width: 15%"><em>Ciclo</em></th> --}}
            </tr>
            <tr style="font-weight: regular; font-size: 0.8rem;">
                <th style="border: 1px solid; padding: 0.3rem; width: 15%; font-weight: regular;">{{ $codigo }}</th>
                <th style="border: 1px solid; padding: 0.3rem; width: 85%; font-weight: regular;" align="left" colspan="3">{{ $nombre }}</th>
                {{-- <th style="border: 1px solid; padding: 0.3rem; width: 15%; font-weight: regular;">{{ $ciclo }}</th> --}}
            </tr>
            <tr style="font-weight: bold; font-size: 0.8rem;">
                <th style="border: 1px solid; padding: 0.3rem; width: 15%; font-size: 0.9rem;"><em>Domicilio del Alumno</em></th>
                <th style="border: 1px solid; padding: 0.3rem; width: 70%; font-weight: regular;" align="left" colspan="2">{{ $domicilio }}</th>
                <th style="border: 1px solid; padding: 0.3rem; width: 15%"><em>Teléfono: </em><span style="font-weight: regular;"><br>{{ $celular }}</span></th>
            </tr>
            <tr style="font-weight: bold;">
                <th style="border: 1px solid; padding: 0.3rem; width: 15%"><em>Código de Asignatura</em></th>
                <th style="border: 1px solid; padding: 0.3rem; width: 55%"><em>Nombre del Curso</em></th>
                <th style="border: 1px solid; padding: 0.3rem; width: 15%"><em>Ciclo</em></th>
                <th style="border: 1px solid; padding: 0.3rem; width: 15%"><em>Créditos</em></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cursos as $item)
            <tr>
                <td style="border: 1px solid; padding: 0.3rem; font-size: 0.8rem;" align="center">{{ $item->cursoProgramaPlan->curso->curso_codigo }}</td>
                <td style="border: 1px solid; padding: 0.3rem; font-size: 0.8rem;">{{ $item->cursoProgramaPlan->curso->curso_nombre }}</td>
                <td style="border: 1px solid; padding: 0.3rem; font-size: 0.8rem;" align="center">{{ $item->cursoProgramaPlan->curso->ciclo->ciclo }}</td>
                <td style="border: 1px solid; padding: 0.3rem; font-size: 0.8rem;" align="center">{{ $item->cursoProgramaPlan->curso->curso_credito }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right" style="border: 1px solid; padding: 0.3rem; font-weight: bold; margin-right: 10px"><em>TOTAL</em></td>
                <td style="border: 1px solid; padding: 0.3rem; font-size: 0.8rem;" align="center">{{ $cursos->sum('cursoProgramaPlan.curso.curso_credito') }}</td>
            </tr>
        </tfoot>
    </table>

    <table
        style="padding-right: 6rem; padding-left: 6rem; padding-top: 7rem; font-weight: bold; font-size: 0.9rem; width: 100%">
        <tbody>
            <tr>
                <td align="center">
                    ..............................................
                </td>
            </tr>
            <tr>
                <td align="center">
                    Firma del Alumno
                </td>
            </tr>
        </tbody>
    </table>
    <div style="position: absolute; right: 70px; top: 210px;">
        <img src="{{ public_path('assets_pdf/sello-posgrado.png') }}" width="110px" alt="sello posgrado">
    </div>
</body>
</html>
