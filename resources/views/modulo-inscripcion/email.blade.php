<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Plantilla Correo
    </title>
</head>

<body style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
    <main>
        <h1 style="color: #212121; margin-bottom: 0.5rem; font-weight: 700; text-align: center;">
            Escuela de Posgrado
        </h1>
        <p style="margin-bottom: 1rem;">
            Estimado/a {{ $nombre }},
        </p>
        @php
            $tipo_correo = $tipo_correo ?? 'create';
        @endphp
        @if (isset($tipo_correo))
            @if ($tipo_correo === 'create')
                <p style="margin-bottom: 1rem;">
                    ¡Gracias por inscribirte!
                </p>
                <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
                    Nos complace informarte que hemos recibido tu registro de inscripción para el proceso de
                    {{ formatearAdmisionVisual($admision) }}. Agradecemos tu interés en nuestro programa y estamos entusiasmados por brindarte
                    una experiencia educativa valiosa. Se le hace recordar que su inscripción esta sujeta a la
                    verificación de los expedientes presentados y el pago de la inscripción. Cualquier observación
                    será comunicada a su correo electrónico.
                </p>
            @elseif ($tipo_correo === 'update')
                <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
                    Nos complace informarte que hemos actualizado tu registro de inscripción y por ello su ficha de
                    inscripción
                    para el proceso de {{ formatearAdmisionVisual($admision) }}. Agradecemos tu interés en nuestro programa y estamos
                    entusiasmados
                    por brindarte una experiencia educativa valiosa. Se le hace recordar que su inscripción esta sujeta a la
                    verificación de los expedientes presentados y el pago de la inscripción. Cualquier observación será
                    comunicada a su correo electrónico.
                </p>
            @endif
        @else
            <p style="margin-bottom: 1rem;">
                ¡Gracias por inscribirte!
            </p>
            <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
                Nos complace informarte que hemos recibido tu registro de inscripción para el proceso de {{ formatearAdmisionVisual($admision) }}.
                Agradecemos tu interés en nuestro programa y estamos entusiasmados por brindarte una experiencia educativa
                valiosa. Se le hace recordar que su inscripción esta sujeta a la verificación de los expedientes presentados
                y el pago de la inscripción. Cualquier observación será comunicada a su correo electrónico.
            </p>
        @endif
        <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
            A continuación, te indicamos el link de acceso al grupo de WhatsApp {{ $programa }}:
        </p>
        <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
            <strong>Link:</strong> {{ $link }}
        </p>
        <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
            Adjunto a este correo, encontrarás el detalle de tu ficha de inscripción. Si tienes alguna pregunta o
            necesitas más información, por favor no dudes en contactarnos. Estamos aquí para ayudarte.
        </p>
        <p style="margin-bottom: 1rem;">
            Atentamente,
        </p>
        <p>
            Escuela de Posgrado
        </p>
    </main>
</body>

</html>
