<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Crear Correo
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('administrador.dashboard') }}" class="text-muted text-hover-primary">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('administrador.gestion-correo') }}" class="text-muted text-hover-primary">
                            Gestión de Correos
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Crear Correos
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid pt-5">
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="row g-5">
                        <div class="col-md-12">
                            <span class="fs-2 fw-bold">
                                Crear un nuevo correo
                            </span>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-check-custom mb-3">
                                <input class="form-check-input" type="radio" wire:model="tipo_envio" value="2"
                                    id="envio_masivo" />
                                <label class="form-check-label" for="envio_masivo">
                                    Envio masivo
                                </label>
                            </div>
                            <div class="form-check form-check-custom">
                                <input class="form-check-input" type="radio" wire:model="tipo_envio" value="1"
                                    id="envio_individual" />
                                <label class="form-check-label" for="envio_individual">
                                    Envio individual
                                </label>
                            </div>
                        </div>
                        @if ($tipo_envio == 1)
                            <div class="col-12">
                                <hr>
                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <div class="row g-3">
                                            <div class="col-md-9">
                                                <input
                                                    class="form-control text-muted @error('buscar_dni') is-invalid @enderror"
                                                    type="text" wire:model="buscar_dni"
                                                    placeholder="Buscar persona por dni">
                                                @error('buscar_dni')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-primary hover-elevate-up w-100"
                                                    wire:click="buscar_persona">
                                                    Buscar
                                                </button>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="separator separator-content my-3 text-secondary">
                                                    O
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <input
                                                    class="form-control text-muted @error('correo_electronico') is-invalid @enderror"
                                                    type="text" wire:model="correo_electronico"
                                                    placeholder="Ingrese un correo electrónico">
                                                @error('correo_electronico')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if ($persona)
                                        <div class="col-md-12">
                                            <div class="d-flex flex-column gap-3">
                                                <span>
                                                    Persona: <strong>{{ $persona->nombre_completo }}</strong>
                                                </span>
                                                <span>
                                                    DNI: <strong>{{ $persona->numero_documento }}</strong>
                                                </span>
                                                <span>
                                                    Correo Electrónico: <strong>{{ $persona->correo }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if ($tipo_envio == 2)
                            <div class="col-12">
                                <hr>
                                <div class="row g-5">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-custom mb-3">
                                            <input class="form-check-input" type="radio" wire:model="tipo_envio_tabla"
                                                value="1" id="inscripciones" />
                                            <label class="form-check-label" for="inscripciones">
                                                Inscripciones
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom mb-3">
                                            <input class="form-check-input" type="radio" wire:model="tipo_envio_tabla"
                                                value="2" id="admitidos" />
                                            <label class="form-check-label" for="admitidos">
                                                Admitidos
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom">
                                            <input class="form-check-input" type="radio" wire:model="tipo_envio_tabla"
                                                value="3" id="otros" />
                                            <label class="form-check-label" for="otros">
                                                Otros
                                            </label>
                                        </div>
                                    </div>
                                    @if ($tipo_envio_tabla != 3)
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Proceso de Admisión</label>
                                            <select class="form-select" wire:model="proceso" id="proceso_filtro">
                                                <option value="">
                                                    Seleccione el Proceso
                                                </option>
                                                @foreach ($procesos as $item)
                                                    <option value="{{ $item->id_admision }}">
                                                        {{ formatearAdmisionVisual($item->admision) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Modalidad del Programa</label>
                                            <select class="form-select" wire:model="modalidad" id="modalidad_filtro">
                                                <option value="">
                                                    Seleccione la Modalidad
                                                </option>
                                                @foreach ($modalidades as $item)
                                                    <option value="{{ $item->id_modalidad }}">
                                                        {{ $item->modalidad }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Programa</label>
                                            <select class="form-select" wire:model="programa" id="programa_filtro">
                                                <option>
                                                    Seleccione el Programa
                                                </option>
                                                @foreach ($programas as $item)
                                                    <option value="{{ $item->id_programa }}">
                                                        {{ $item->programa }} EN {{ $item->subprograma }}
                                                        @if ($item->mencion)
                                                            CON MENCION EN {{ $item->mencion }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            Cantidad de correos que se enviará: {{ $cantidad_correos }}
                                        </div>
                                    @else
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold required">
                                            Subir TXT con los correos
                                        </label>
                                        <input type="file" wire:model="archivo" class="form-control {{ $errors->has('archivo') ? 'is-invalid' : ($archivo ? 'is-valid' : '') }}">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body mb-0">
                    <div class="row g-5">
                        <div class="col-12">
                            <label for="asunto" class="form-label required">
                                Asunto
                            </label>
                            <input wire:model="asunto" type="text" id="asunto"
                                class="form-control @error('asunto') is-invalid @enderror"
                                placeholder="Ingrese el asunto del correo">
                            @error('asunto')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="mensaje" class="form-label required">
                                Mensaje
                            </label>
                            <div wire:ignore>
                                <textarea class="form-control @error('mensaje') is-invalid @enderror" wire:model="mensaje" id="mensaje"
                                    placeholder="Ingrese el mensaje del correo">
                                </textarea>
                            </div>
                            @error('mensaje')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success hover-elevate-up w-100" wire:click="enviar_correo" wire:loading.attr="disabled" wire:target="enviar_correo">
                                <span wire:loading.remove wire:target="enviar_correo">
                                    Enviar Correo
                                </span>
                                <span wire:loading wire:target="enviar_correo">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(function() {
                $('#mensaje').summernote({
                    placeholder: 'Ingrese el mensaje del correo',
                    height: 300,
                    tabsize: 2,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    callbacks: {
                        onChange: function(contents, $editable) {
                            @this.set('mensaje', contents);
                        }
                    }
                });
            })
        </script>
    @endpush
</div>
