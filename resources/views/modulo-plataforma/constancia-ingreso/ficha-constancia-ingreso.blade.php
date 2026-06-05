<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Constancia de Ingreso - {{ $nombre }}</title>
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
        style="width:100%; padding-right: 1rem; padding-left: 1rem; padding-bottom: 2.5rem; padding-top: 2.5rem;">
        <thead>
            <tr>
                <th>
                    <div style="display: flex; align-items: center; margin-left: 35px;">
                        <img src="{{ public_path('assets_pdf/unu.png') }}" width="80px" height="100px"
                            alt="logo unu">
                    </div>
                </th>
                <th>
                    <div style="text-align: center">
                        <div class="" style="font-weight: 700; font-size: 1.6rem;">
                            UNIVERSIDAD NACIONAL DE UCAYALI
                        </div>
                        <div style="margin: 0.2rem"></div>
                        <div class="" style="font-weight: 700; font-size: 1.7rem;">
                            Escuela de Posgrado
                        </div>
                    </div>
                </th>
                <th>
                    <div style="display: flex; align-items: center; margin-right: 35px;">
                        <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="80px" height="100px"
                            alt="logo posgrado">
                    </div>
                </th>
            </tr>
        </thead>
    </table>

    <div
        style="padding: 3rem; text-align: center; font-size: 3rem; font-weight: bold; font-family: 'Varela Round', sans-serif; color: rgb(40, 49, 132)">
        Constancia de Ingreso
    </div>

    <div
        style="padding-right: 7rem; padding-left: 7rem; padding-top: 2.7rem; font-weight: bold; font-size: 1.2rem; text-align: center;">
        EL QUE SUSCRIBE, DIRECTOR DE LA ESCUELA DE<br>POSGRADO DE LA UNIVERSIDAD NACIONAL DE UCAYALI.
    </div>

    <div
        style="padding-right: 7rem; padding-left: 7rem; padding-top: 2.7rem; font-weight: bold; font-size: 1.2rem; text-align: center;">
        HACE CONSTAR QUE:
    </div>

    <div style="padding-right: 7rem; padding-left: 7rem; padding-top: 2.7rem; font-size: 1.2rem; text-align: justify;">
        <strong>{{ $nombre }}</strong> con código de matrícula <strong>{{ $codigo }}</strong>, ha ingresado
        a la Escuela de Posgrado de la Universidad Nacional de Ucayali - {{ formatearAdmisionVisual($admision) }} en
        <strong>{{ $programa }}</strong> con modalidad <strong>{{ $modalidad }}</strong>.
    </div>

    <div style="padding-right: 7rem; padding-left: 7rem; padding-top: 2rem; font-size: 1.2rem; text-align: justify;">
        Se expide la presente constancia a solicitud del interesado para los fines que estime conveniente.
    </div>

    <div style="padding-right: 7rem; padding-left: 7rem; padding-top: 2rem; font-size: 1.2rem; text-align: right;">
        {{ $fecha }}
    </div>

    <div style="text-align: center;  padding-top: 0.8rem;">
        <img src="{{ public_path('assets_pdf/firma_doctor_posgrado.png') }}" alt="firma" width="300">
    </div>

    <div style="padding-right: 7rem; padding-left: 7rem; padding-top: 0.8rem; font-size: 1rem;">
        {{-- {{ $codigo_constancia }}
        {!! QrCode::size(50)->generate($codigo_constancia) !!} --}}
        <img src="data:image/svg+xml;base64,{{ base64_encode($codigo_constancia) }}" alt="qr" width="100">
    </div>
</body>

</html>
