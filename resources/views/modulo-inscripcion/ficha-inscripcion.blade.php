<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Inscripcion</title>
    <style>
        * {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
        }
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
        }
        body {
        line-height: 1;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
            Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
        }
        .titulo {
        font-weight: 700;
        font-size: large;
        text-align: initial;
        }
        .titulo2 {
        font-weight: 700;
        font-size: medium;
        text-align: initial;
        }
        .titulo3 {
        font-weight: 700;
        font-size: small;
        text-align: initial;
        }
        .titulo4{
        font-weight: 700;
        font-size: small;
        }
        .titulo6{
        font-weight: 700;
        font-size: medium;
        }
        .titulo5 {
            font-size: small;
            text-align: initial;
        }
        .linea {
        border-top: 1px solid black;
        height: 5px;
        max-width: 100%;
        padding: 0;
        }
        .linea2 {
        border-top: 1px solid black;
        height: 5px;
        max-width: 45%;
        padding: 0;
        }
        .parent {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        grid-template-rows: repeat(3, 1fr);
        grid-column-gap: 0px;
        grid-row-gap: 0px;
        }
        hr {
        height: 100vh;
        width: 0.5vw;
        border-width: 0;
        color: #000;
        background-color: #000;
        }
        .tabla4 {
        padding: 5px;
        border:1px solid black;
        }
        .tablita{
        border-collapse: collapse;
        }

        .d-flex {
        display: flex !important;
        }

        .justify-content-start {
        justify-content: flex-start !important;
        }
        .justify-content-end {
        justify-content: flex-end !important;
        }
        .justify-content-center {
        justify-content: center !important;
        }
        .justify-content-between {
        justify-content: space-between !important;
        }
        .justify-content-around {
        justify-content: space-around !important;
        }
        .justify-content-evenly {
        justify-content: space-evenly !important;
        }
        .align-items-start {
        align-items: flex-start !important;
        }
        .align-items-end {
        align-items: flex-end !important;
        }
        .align-items-center {
        align-items: center !important;
        }
        .align-items-baseline {
        align-items: baseline !important;
        }
        .align-items-stretch {
        align-items: stretch !important;
        }
        .align-content-start {
        align-content: flex-start !important;
        }
        .align-content-end {
        align-content: flex-end !important;
        }
        .align-content-center {
        align-content: center !important;
        }
        .align-content-between {
        align-content: space-between !important;
        }
        .align-content-around {
        align-content: space-around !important;
        }
        .align-content-stretch {
        align-content: stretch !important;
        }
        .align-self-auto {
        -ms-flex-item-align: auto !important;
        align-self: auto !important;
        }
        .align-self-start {
        -ms-flex-item-align: start !important;
        align-self: flex-start !important;
        }
        .align-self-end {
        -ms-flex-item-align: end !important;
        align-self: flex-end !important;
        }
        .align-self-center {
        -ms-flex-item-align: center !important;
        align-self: center !important;
        }
        .align-self-baseline {
        -ms-flex-item-align: baseline !important;
        align-self: baseline !important;
        }
        .align-self-stretch {
        -ms-flex-item-align: stretch !important;
        align-self: stretch !important;
        }
        .m-0 {
        margin: 0 !important;
        }
        .m-1 {
        margin: 0.25rem !important;
        }
        .m-2 {
        margin: 0.5rem !important;
        }
        .m-3 {
        margin: 1rem !important;
        }
        .m-4 {
        margin: 1.5rem !important;
        }
        .m-5 {
        margin: 3rem !important;
        }
        .m-auto {
        margin: auto !important;
        }
        .mx-0 {
        margin-right: 0 !important;
        margin-left: 0 !important;
        }
        .mx-1 {
        margin-right: 0.25rem !important;
        margin-left: 0.25rem !important;
        }
        .mx-2 {
        margin-right: 0.5rem !important;
        margin-left: 0.5rem !important;
        }
        .mx-3 {
        margin-right: 1rem !important;
        margin-left: 1rem !important;
        }
        .mx-4 {
        margin-right: 1.5rem !important;
        margin-left: 1.5rem !important;
        }
        .mx-5 {
        margin-right: 3rem !important;
        margin-left: 3rem !important;
        }
        .mx-auto {
        margin-right: auto !important;
        margin-left: auto !important;
        }
        .my-0 {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        }
        .my-1 {
        margin-top: 0.25rem !important;
        margin-bottom: 0.25rem !important;
        }
        .my-2 {
        margin-top: 0.5rem !important;
        margin-bottom: 0.5rem !important;
        }
        .my-3 {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
        }
        .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
        }
        .my-5 {
        margin-top: 3rem !important;
        margin-bottom: 3rem !important;
        }
        .my-auto {
        margin-top: auto !important;
        margin-bottom: auto !important;
        }
        .mt-0 {
        margin-top: 0 !important;
        }
        .mt-1 {
        margin-top: 0.25rem !important;
        }
        .mt-2 {
        margin-top: 0.5rem !important;
        }
        .mt-3 {
        margin-top: 1rem !important;
        }
        .mt-4 {
        margin-top: 1.5rem !important;
        }
        .mt-5 {
        margin-top: 3rem !important;
        }
        .mt-auto {
        margin-top: auto !important;
        }
        .me-0 {
        margin-right: 0 !important;
        }
        .me-1 {
        margin-right: 0.25rem !important;
        }
        .me-2 {
        margin-right: 0.5rem !important;
        }
        .me-3 {
        margin-right: 1rem !important;
        }
        .me-4 {
        margin-right: 1.5rem !important;
        }
        .me-5 {
        margin-right: 3rem !important;
        }
        .me-auto {
        margin-right: auto !important;
        }
        .mb-0 {
        margin-bottom: 0 !important;
        }
        .mb-1 {
        margin-bottom: 0.25rem !important;
        }
        .mb-2 {
        margin-bottom: 0.5rem !important;
        }
        .mb-3 {
        margin-bottom: 1rem !important;
        }
        .mb-4 {
        margin-bottom: 1.5rem !important;
        }
        .mb-5 {
        margin-bottom: 3rem !important;
        }
        .mb-auto {
        margin-bottom: auto !important;
        }
        .ms-0 {
        margin-left: 0 !important;
        }
        .ms-1 {
        margin-left: 0.25rem !important;
        }
        .ms-2 {
        margin-left: 0.5rem !important;
        }
        .ms-3 {
        margin-left: 1rem !important;
        }
        .ms-4 {
        margin-left: 1.5rem !important;
        }
        .ms-5 {
        margin-left: 3rem !important;
        }
        .ms-auto {
        margin-left: auto !important;
        }
        .p-0 {
        padding: 0 !important;
        }
        .p-1 {
        padding: 0.25rem !important;
        }
        .p-2 {
        padding: 0.5rem !important;
        }
        .p-3 {
        padding: 1rem !important;
        }
        .p-4 {
        padding: 1.5rem !important;
        }
        .p-5 {
        padding: 3rem !important;
        }
        .px-0 {
        padding-right: 0 !important;
        padding-left: 0 !important;
        }
        .px-1 {
        padding-right: 0.25rem !important;
        padding-left: 0.25rem !important;
        }
        .px-2 {
        padding-right: 0.5rem !important;
        padding-left: 0.5rem !important;
        }
        .px-3 {
        padding-right: 1rem !important;
        padding-left: 1rem !important;
        }
        .px-4 {
        padding-right: 1.5rem !important;
        padding-left: 1.5rem !important;
        }
        .px-5 {
        padding-right: 3rem !important;
        padding-left: 3rem !important;
        }
        .py-0 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        }
        .py-1 {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
        }
        .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
        }
        .py-3 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        }
        .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
        }
        .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
        }
        .pt-0 {
        padding-top: 0 !important;
        }
        .pt-1 {
        padding-top: 0.25rem !important;
        }
        .pt-2 {
        padding-top: 0.5rem !important;
        }
        .pt-3 {
        padding-top: 1rem !important;
        }
        .pt-4 {
        padding-top: 1.5rem !important;
        }
        .pt-5 {
        padding-top: 3rem !important;
        }
        .pe-0 {
        padding-right: 0 !important;
        }
        .pe-1 {
        padding-right: 0.25rem !important;
        }
        .pe-2 {
        padding-right: 0.5rem !important;
        }
        .pe-3 {
        padding-right: 1rem !important;
        }
        .pe-4 {
        padding-right: 1.5rem !important;
        }
        .pe-5 {
        padding-right: 3rem !important;
        }
        .pb-0 {
        padding-bottom: 0 !important;
        }
        .pb-1 {
        padding-bottom: 0.25rem !important;
        }
        .pb-2 {
        padding-bottom: 0.5rem !important;
        }
        .pb-3 {
        padding-bottom: 1rem !important;
        }
        .pb-4 {
        padding-bottom: 1.5rem !important;
        }
        .pb-5 {
        padding-bottom: 3rem !important;
        }
        .ps-0 {
        padding-left: 0 !important;
        }
        .ps-1 {
        padding-left: 0.25rem !important;
        }
        .ps-2 {
        padding-left: 0.5rem !important;
        }
        .ps-3 {
            padding-left: 1rem !important;
        }
        .ps-4 {
            padding-left: 1.5rem !important;
        }
        .ps-5 {
            padding-left: 3rem !important;
        }
    </style>
</head>
<body style="padding: 0rem; position: relative;">
    <table class="table" style="width:100%; background: #0a2e5c; padding: 1.5rem;">
        <thead>
            <tr>
                <th>
                    <div style="display: flex; align-items: center; margin-left: 35px;">
                        <img src="{{ public_path('assets_pdf/unu.png') }}" width="75px" height="85px" alt="logo unu">
                    </div>
                </th>
                <th style="color: white">
                    <div style="text-align: center">
                        <div class="mb-3" style="font-weight: 700; font-size:large;">
                            UNIVERSIDAD NACIONAL DE UCAYALI
                        </div>
                        <div class="mb-3" style="font-weight: 700; font-size:medium;">
                            ESCUELA DE POSGRADO
                        </div>
                        <div style="font-weight: 700; font-size:medium;">
                            FICHA DE INSCRIPCION: {{ formatearAdmisionVisual($admision) }}
                        </div>
                    </div>
                </th>
                <th>
                    <div style="display: flex; align-items: center; margin-right: 35px;">
                        <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="75px" height="85px" alt="logo posgrado">
                    </div>
                </th>
            </tr>
        </thead>
    </table>
    <div style="padding-left: 1.5rem; padding-right: 1.5rem;">
        <table class="table mt-4" style="width:100%; padding-left: 0.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.2rem; background-color: rgb(200, 229, 255);">
            <thead>
                <tr style="">
                    <th align="left" style="text-align: left">
                        <div class="titulo6">
                            Sede: {{ $programa->programa_plan->programa->sede->sede }}
                        </div>
                    </th>
                    <th align="right" style="text-align: right">
                        <div class="titulo6">
                            Nro Ficha: {{$inscripcion_codigo}}
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
        <div class="linea">
        </div>
        <div style="margin-top: 0.6rem;"></div>
        <div class="">
            <div class="mt-2">
                <div class="titulo2" style="padding-left: 0.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.3rem; background-color: rgb(200, 229, 255);">
                    INFORMACIÓN DE INSCRIPCIÓN
                </div>
                <div class="linea mb-2"></div>
                <div>
                    <table>
                        <tr>
                            <th><div class="titulo3">Fecha de inscripción</div></th>
                            <th><div class="mx-2">:</div></th>
                            <th style="text-align: initial;">{{ date('h:i a d/m/Y', strtotime($inscripcion->created_at)) }}</th>
                        </tr>
                        <tr>
                            <th><div class="titulo3">Programa</div></th>
                            <th><div class="mx-2">:</div></th>
                            <th style="text-align: initial;">{{ ucwords(strtolower($programa->programa_plan->programa->programa)) }}</th>
                        </tr>
                        <tr>
                            <th><div class="titulo3">{{ ucwords(strtolower($programa->programa_plan->programa->programa))  }}</div></th>
                            <th><div class="mx-2">:</div></th>
                            <th style="text-align: initial;">{{ ucwords(strtolower($programa->programa_plan->programa->subprograma )) }}</th>
                        </tr>
                        <tr>
                            @if ($programa->programa_plan->programa->mencion)
                            <th><div class="titulo3">Mención</div></th>
                            <th><div class="mx-2">:</div></th>
                            <th style="text-align: initial;">{{ $programa->programa_plan->programa->mencion }}</th>
                            @endif
                        </tr>
                    </table>
                </div>
            </div>
            <div style="margin-top: 0.6rem;"></div>
            <table class="table" style="width:100%;">
                <thead>
                    <tr>
                        <th>
                            <div class="mt-2">
                                <div class="titulo2" style="padding-left: 0.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.3rem; background-color: rgb(200, 229, 255);">
                                    INFORMACIÓN PERSONAL
                                </div>
                                <div class="linea mb-2"></div>
                                <div>
                                    <table>
                                        <tr>
                                            <th><div class="titulo3">Documento de identidad</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->numero_documento }}</th>
                                        </tr>
                                        <tr>
                                            <th><div class="titulo3">Apellidos</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}</th>
                                        </tr>
                                        <tr>
                                            <th><div class="titulo3">Nombres</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->nombre }}</th>
                                        </tr>
                                        <tr>
                                            <th><div class="titulo3">Fecha de nacimiento</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{date('d/m/Y', strtotime($persona->fecha_nacimiento)) }}</th>
                                        </tr>
                                        <tr>
                                            <th><div class="titulo3">Sexo</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->genero->genero }}</th>
                                        </tr>
                                        <tr>
                                            <th><div class="titulo3">Estado Civil</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->estado_civil->estado_civil }}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </th>
                        <th>
                            <div class="mt-2">
                                <div class="titulo2" style="padding-left: 0.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.3rem; background-color: rgb(200, 229, 255);">
                                    INFORMACIÓN DE CONTACTO
                                </div>
                                <div class="linea mb-2"></div>
                                <div>
                                    <table>
                                        <tr>
                                            <th><div class="titulo3">Domicilio</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->direccion }}</th>
                                        </tr>
                                        <tr>
                                            <th><div class="titulo3">Correo electronico</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->correo }}</th>
                                        </tr>
                                        <tr>
                                            <th><div class="titulo3">Celular</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->celular }}</th>
                                        </tr>
                                        @if ($persona->celular_opcional)
                                        <tr>
                                            <th><div class="titulo3">Celular opcional</div></th>
                                            <th><div class="mx-2">:</div></th>
                                            <th style="text-align: initial;">{{ $persona->celular_opcional }}</th>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
        <div style="margin-top: 0.6rem;"></div>
        <div class="mt-2">
            <div class="titulo2" style="padding-left: 0.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.3rem; background-color: rgb(200, 229, 255);">
                INFORMACIÓN DE DOCUMENTOS
            </div>
            <div class="linea mb-2"></div>
            <div>
                <table>
                    @php
                        $value = 0;
                    @endphp
                    @foreach ($expediente as $item2)
                        @foreach ($expediente_inscripcion as $item)
                            @if($item2->id_expediente_admision == $item->id_expediente_admision)
                            <tr>
                                <th style="white-space: initial;"><div class="titulo3">{{ $item2->expediente }}</div></th>
                                <th><div class="mx-2">:</div></th>
                                <th style="text-align: initial;">Entregado ({{date('d/m/Y', strtotime($item->expediente_inscripcion_fecha))}})</th>
                            </tr>
                            @php $value = 1; @endphp
                            @endif
                        @endforeach
                        @if($value != 1)
                            <tr>
                                <th style="white-space: initial;"><div class="titulo3">{{ $item2->expediente }}</div></th>
                                <th><div class="mx-2">:</div></th>
                                <th style="text-align: initial; color: red;">No Entregado</th>
                            </tr>
                        @endif
                        @php $value = 0; @endphp
                    @endforeach
                </table>
            </div>
        </div>
        <div style="margin-top: 0.6rem;"></div>
        <div class="mt-2">
            <div class="titulo2" style="padding-left: 0.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.3rem; background-color: rgb(200, 229, 255);">
                INFORMACIÓN DE PAGO
            </div>
            <div class="linea mb-2"></div>
            <div>
                <table>
                    <tr>
                        <th><div class="titulo3">Concepto de pago</div></th>
                        <th><div class="mx-2">:</div></th>
                        <th style="text-align: initial;">{{ $pago->concepto_pago->concepto_pago }}</th>
                    </tr>
                </table>
            </div>
            <table class="mt-2 tablita" width="100%">
                <tr style="background-color: rgb(200, 229, 255)">
                    <th class="tabla4" width="35%" align="center"><div class="titulo4">Fecha</div></th>
                    <th class="tabla4" width="35%" align="center"><div class="titulo4">Nro. Operación</div></th>
                    <th class="tabla4" align="center"><div class="titulo4 tabla">Importe</div></th>
                </tr>
                <tr>
                    <th class="tabla4"><div class="titulo5" style="text-align: center">{{date('d/m/Y', strtotime($pago->pago_fecha)) }}</div></th>
                    <th class="tabla4"><div class="titulo5" style="text-align: center">{{ $pago->pago_operacion }}</div></th>
                    <th class="tabla4"><div class="titulo5" style="text-align: center">{{ $pago->pago_monto }}</div></th>
                </tr>
                <tr>
                    <th><div class="titulo3"></div></th>
                    <th class="tabla4"><div class="titulo3" style="text-align: center">Total</div></th>
                    <th class="tabla4"><div class="titulo3" style="text-align: center">S/. {{number_format($pago_monto,2)}}</div></th>
                </tr>
            </table>
        </div>
        <div style="margin-top: 0.6rem;"></div>
        @if ($seguimiento_count > 0)
        <div style="font-size: small; text-align: justify; margin-top: 0.6rem; margin-bottom: 0.6rem; font-weight: 700;">
            Declaro bajo juramento regularizar mi constancia de registro ante la SUNEDU en un plazo máximo de 6 meses hábiles en el caso de Maestría, o de 1 año en el caso de Doctorado, caso contrario se procederá a la cancelación de mi ingreso a la Escuela de Postgrado.
        </div>
        @endif
        <table class="table"  style="width:100%; margin-top: 2.5rem;">
            <thead>
                <tr>
                    <th class="" style="text-align: initial;">
                        Para consultar los resultados de la evaluación de expediente, entrevista personal y subida de expedientes deberá acceder al siguiente enlace utilizando sus credenciales de usuario y contraseña que se les entregará a continuación:
                    </th>
                </tr>
            </thead>
        </table>
        <div style="margin-top: 0.5rem"></div>
        <table class="tablita">
            <thead>
                <tr>
                    <th class="tabla4" style="background-color: rgb(200, 229, 255)"><div class="titulo3" style="font-size: small;">Usuario</div></th>
                    <th class="tabla4" style="text-align: initial; font-size: small;">{{ $persona->correo }}</th>
                </tr>
                <tr>
                    <th class="tabla4" style="background-color: rgb(200, 229, 255)"><div class="titulo3" style="font-size: small;">Contraseña</div></th>
                    <th class="tabla4" style="text-align: initial; font-size: small;">{{ $persona->numero_documento }}</th>
                </tr>
                <tr>
                    <th class="tabla4" style="background-color: rgb(200, 229, 255)"><div class="titulo3" style="font-size: small;">Link</div></th>
                    <th class="tabla4" style="text-align: initial; font-size: small;">http://posgrado.unu.edu.pe/plataforma/login</th>
                </tr>
            </thead>
        </table>
        <table class="table" style="width:100%;">
            <thead>
                <tr>
                    <th align="right" style="text-align: right;">
                        <div class="titulo4">
                            ESTADO: INSCRITO
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    <div style="position: absolute; right: 100px; top: 220px;">
        <img src="{{ public_path('/assets_pdf/sello-posgrado.png') }}" width="110px" alt="sello posgrado">
    </div>
</body>
</html>
