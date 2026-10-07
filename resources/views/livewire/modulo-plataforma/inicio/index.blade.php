<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Inicio
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Inicio</li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row g-0">
                <div class="col-md-12">
                    @if ($inscripcion)
                        @if ($inscripcion->inscripcion_estado == 0)
                            <div
                                class="alert alert-dismissible bg-light-warning border border-3 border-warning d-flex flex-center flex-column py-10 px-10 px-lg-20">
                                <i class="ki-outline ki-information fs-5tx text-warning mb-5"></i>

                                <div class="text-center">
                                    <h1 class="fw-bold mb-5">
                                        ¡Bienvenido a la Plataforma del Estudiante de la Escuela de Posgrado!
                                    </h1>

                                    <div class="separator separator-dashed border-warning opacity-25 mb-5"></div>

                                    <div class="text-gray-900 fs-5">
                                        <strong>
                                            Estimado(a) postulante, se le informa que la inscripción realizada está en
                                            proceso
                                            de verificación, por favor espere la verificación, caso contrario, se le
                                            notificará
                                            por correo electrónico.
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div
                                class="alert alert-dismissible bg-light-success border border-3 border-success d-flex flex-center flex-column py-10 px-10 px-lg-20">
                                <i class="ki-outline ki-like fs-5tx text-success mb-5"></i>

                                <div class="text-center">
                                    <h1 class="fw-bold mb-5">
                                        ¡Bienvenido a la Plataforma del Estudiante de la Escuela de Posgrado!
                                    </h1>

                                    <div class="separator separator-dashed border-success opacity-25 mb-5"></div>

                                    <div class="text-gray-900 fs-5">
                                        <strong>
                                            Estimado(a) postulante, se le informa que su inscripción ha sido verificada, por
                                            favor espere las evaluaciones correspondientes a este proceso de admisión, se le
                                            estará notificando por correo electrónico y en el modulo de
                                            <a href="{{ route('plataforma.admision') }}">
                                                "Proceso de Admisión" -> "Admisión"
                                            </a>.
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-dismissible bg-light-success border border-3 border-success d-flex flex-center flex-column py-10 px-10 px-lg-20">
                            <i class="ki-outline ki-like fs-5tx text-success mb-5"></i>

                            <div class="text-center">
                                <h1 class="fw-bold mb-5">
                                    ¡Bienvenido a la Plataforma del Estudiante de la Escuela de Posgrado!
                                </h1>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- <div class="col-md-12">
                    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                Bienvenido a la Plataforma del Estudiante de la Escuela de Posgrado
                            </span>
                        </div>
                    </div>
                </div> --}}
                @if ($programa && $link)
                    <div class="col-md-12">
                        {{-- alerta  --}}
                        <div
                            class="alert bg-light-warning border border-3 border-warning d-flex align-items-center p-5">
                            <i class="ki-outline ki-sms fs-2qx me-4 text-warning"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold fs-5">
                                    LINK DEL GRUPO DE WHATSAPP DE {{ $programa }}: <br>
                                    <a href="{{ $link }}" target="_blank" class="fw-bold">
                                        {{ $link }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($admitido)
                    @if ($admitido->es_traslado_externo == 1)
                    <div class="col-md-12">
                        {{-- alerta  --}}
                        <div class="alert bg-light-danger border border-3 border-danger d-flex align-items-center p-5">
                            <i class="ki-outline ki-information-5 fs-2qx me-4 text-danger"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold fs-5">
                                    Aviso: Por favor, recuerde que debe presentar físicamente sus sílabos y el Certificado de Estudios en las oficinas de la Escuela de Posgrado para proceder con la convalidación de los cursos.
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
                <div class="col-md-12">
                    {{-- alerta  --}}
                    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                Recuerde que toda observacion de su inscripcion, pago y expedientes subidos serán
                                notificados a su correo electronico.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div id="slider" class="carousel carousel-custom slide pe-5" data-bs-ride="carousel"
                        data-bs-interval="5000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('media/banner-2026-05.jpeg') }}" alt="carousel"
                                    class="w-100 rounded" />
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center flex-wrap">
                            <ol
                                class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
                                <li data-bs-target="#slider" data-bs-slide-to="0" class="ms-1 active"></li>
                            </ol>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div id="slider" class="carousel carousel-custom slide pe-5" data-bs-ride="carousel"
                        data-bs-interval="5000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('media/banner-posgrado-ampliacion-matricula.jpeg') }}" alt="carousel"
                                    class="w-100 rounded" />
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center flex-wrap">
                            <ol
                                class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
                                <li data-bs-target="#slider" data-bs-slide-to="0" class="ms-1 active"></li>
                            </ol>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-md-4">
                    <div id="slider" class="carousel carousel-custom slide pe-5" data-bs-ride="carousel"
                        data-bs-interval="5000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('media/banner-posgrado-cuentas.jpeg') }}" alt="carousel"
                                    class="w-100 rounded" />
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center flex-wrap">
                            <ol
                                class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
                                <li data-bs-target="#slider" data-bs-slide-to="0" class="ms-1 active"></li>
                            </ol>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-md-4">
                    <div id="slider" class="carousel carousel-custom slide pe-5" data-bs-ride="carousel"
                        data-bs-interval="5000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('media/banner-posgrado-cronograma.jpeg') }}" alt="carousel"
                                    class="w-100 rounded" />
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center flex-wrap">
                            <ol
                                class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
                                <li data-bs-target="#slider" data-bs-slide-to="0" class="ms-1 active"></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <iframe width="100%" height="350" class="rounded"
                        src="https://www.youtube.com/embed/95hwC62HqXM?si=t43N0gdHWoEUGgGA"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                    </iframe>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- Modal Encuestas -->
    <div wire:init="open_modal_encuesta" wire:ignore.self class="modal fade" id="modal_encuesta"
        data-bs-backdrop="static" tabindex="-1" aria-labelledby="modal_encuesta" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5">
                    <div class="text-center p-5 rounded-4 bg-light-info">
                        <span class="svg-icon svg-icon-info svg-icon-5hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM11.7 17.7L16 14C16.4 13.6 16.4 12.9 16 12.5C15.6 12.1 15.4 12.6 15 13L11 16L9 15C8.6 14.6 8.4 14.1 8 14.5C7.6 14.9 8.1 15.6 8.5 16L10.3 17.7C10.5 17.9 10.8 18 11 18C11.2 18 11.5 17.9 11.7 17.7Z"
                                    fill="currentColor" />
                                <path
                                    d="M10.4343 15.4343L9.25 14.25C8.83579 13.8358 8.16421 13.8358 7.75 14.25C7.33579 14.6642 7.33579 15.3358 7.75 15.75L10.2929 18.2929C10.6834 18.6834 11.3166 18.6834 11.7071 18.2929L16.25 13.75C16.6642 13.3358 16.6642 12.6642 16.25 12.25C15.8358 11.8358 15.1642 11.8358 14.75 12.25L11.5657 15.4343C11.2533 15.7467 10.7467 15.7467 10.4343 15.4343Z"
                                    fill="currentColor" />
                                <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                            </svg>
                        </span>
                    </div>

                    <form autocomplete="off" class="mt-5">
                        <h3 class="mb-3 text-center fw-bold">
                            Encuesta
                            </h4>
                            <div class="mt-5 text-center">
                                <span class="fs-5 fw-bold">
                                    ¿Cómo se enteró de este proceso de admisión? <i
                                        class="las la-info-circle fs-3 text-primary ms-2" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" title="Puede seleccionar mas de una opción"></i>
                                </span>
                            </div>
                            <div class="mt-5 mb-5 mx-5 px-5">
                                @foreach ($encuestas as $item)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $item->id_encuesta }}" id="{{ $item->id_encuesta }}"
                                            wire:model="encuesta" wire:key="{{ $item->id_encuesta }}">
                                        <label class="fs-5" for="{{ $item->id_encuesta }}"
                                            wire:key="{{ $item->id_encuesta }}">
                                            {{ $item->encuesta }}
                                        </label>
                                    </div>
                                @endforeach
                                @if ($mostra_otros == true)
                                    <div class="mt-3">
                                        <div>
                                            <textarea class="form-control" placeholder="Especifique otro" wire:model="encuesta_otro" data-kt-autosize="true"></textarea>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="hstack gap-2 justify-content-center">
                                <button type="button" wire:click="guardar_encuesta"
                                    class="btn btn-info">Guardar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Encuesta Docente
    <div wire:ignore.self class="modal fade" id="modal_encuesta_docente"
        data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5">
                    <form autocomplete="off" class="mt-5">
                        <h3 class="mb-3 text-center fw-bold">
                            Encuesta Evaluación Docente
                            </h4>
                            <div class="my-5 text-center">
                                <div
                                    class="alert alert-dismissible bg-light-warning border border-3 border-warning d-flex flex-center flex-column py-10 px-10 px-lg-20">
                                    <i class="ki-outline ki-information fs-5tx text-warning mb-5"></i>

                                    <div class="text-center">
                                        <div class="text-gray-900 fs-5">
                                            <strong>
                                                Estimado(a) estudiante, se le informa que tiene encuestas de evalaución docente pendientes.
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('plataforma.evaluacion-docentes') }}" class="btn btn-info w-100">
                                Ir a encuesta
                            </a>
                    </form>
                </div>
            </div>
        </div>
    </div>-->
    <!-- Modal alerta admitido
    @if ($admitido)
        @if ($admitido->admitido_alerta == 0)
            <div wire:init="alerta_admitido"></div>
        @endif
    @endif
    <div wire:ignore.self class="modal fade" id="modal-alerta-admitido"
        data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-light-success">
                <div class="modal-header py-2">
                    <h3 class="modal-title fw-bolder text-success">¡Admitido!</h3>
                    @if ($admitido)
                        <div class="btn btn-icon btn-sm btn-active-light-danger" wire:click="cerrar_alerta_admitido({{ $admitido->id_admitido }})">
                            <i class="ki-outline ki-cross fs-2x"></i>
                        </div>
                    @endif
                </div>
                <div class="modal-body">
                    <div
                        class="d-flex flex-column align-items-center gap-3 bg-light-success border border-3 border-success rounded">
                        <lord-icon src="https://cdn.lordicon.com/fkmafinl.json" trigger="in"
                            style="width:200px;height:200px">
                        </lord-icon>
                        <span class="fs-1 mb-5 text-center text-success" style="font-weight: 800">
                            ¡Fuiste admitido a la Escuela de Posgrado!
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
@push('scripts')
    <script></script>
@endpush
