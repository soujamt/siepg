<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Links de Whatsapp
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
                            Links de Whatsapp
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modal_links_whatsapp" wire:click="modo()" class="btn btn-primary hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modal_links_whatsapp">Nuevo</a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
                            <div class="d-flex justify-content-between align-items-center gap-4">
                                <a class="btn btn-light-primary me-3 fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    Filtro
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-400px" data-kt-menu="true" id="menu_inscripcion" wire:ignore.self>
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">
                                            Opciones de filtrado
                                        </div>
                                    </div>
                                    <div class="separator border-gray-200"></div>
                                    <div class="px-7 py-5 row">
                                        <div class="mb-5 col-12">
                                            <label class="form-label fw-semibold">Proceso de Admisión:</label>
                                            <div>
                                                <select class="form-select" wire:model="proceso_filtro" id="proceso_filtro"  data-control="select2" data-placeholder="Seleccione el Proceso">
                                                    <option></option>
                                                    @foreach ($admisiones as $item)
                                                        <option value="{{ $item->id_admision }}">{{ formatearAdmisionVisual($item->admision) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-12">
                                            <label class="form-label fw-semibold">Modalidad del Programa:</label>
                                            <div>
                                                <select class="form-select" wire:model="modalidad_filtro" id="modalidad_filtro" data-control="select2" data-placeholder="Seleccione la Modalidad">
                                                    <option></option>
                                                    @foreach ($modalidades as $item)
                                                        <option value="{{ $item->id_modalidad }}">
                                                            {{ $item->id_modalidad == 1 ? 'PRESENCIAL' : 'DISTANCIA' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="button" wire:click="resetear_filtro" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Resetear</button>
                                            <button type="button" class="btn btn-primary" data-kt-menu-dismiss="true" wire:click="filtrar">Aplicar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 w-md-25">
                                <input class="form-control text-muted" type="search" wire:model="search"
                                    placeholder="Buscar...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center" class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col">ID</th>
                                        <th scope="col">Links</th>
                                        <th scope="col">Programa</th>
                                        <th scope="col">Modalidad</th>
                                        <th scope="col">Admision</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($links as $item)
                                        <tr class="fs-6">
                                            <td align="center" class="fw-bold fs-5">
                                                {{ $item->id_link_whatsapp }}
                                            </td>
                                            <td>
                                                {{ $item->link_whatsapp }}
                                            </td>
                                            <td>
                                                @if ($item->mencion)
                                                    MENCION EN {{ $item->mencion }}
                                                @else
                                                    {{ $item->programa }} EN {{ $item->subprograma }}
                                                @endif
                                            </td>
                                            <td align="center">
                                                <span class="badge badge-light-dark fs-6 px-3 py-2">
                                                    {{ $item->id_modalidad == 1 ? 'PRESENCIAL' : 'DISTANCIA' }}
                                                </span>
                                            </td>
                                            <td align="center">
                                                <span class="badge badge-light-info fs-6 px-3 py-2">
                                                    {{ formatearAdmisionVisual($item->admision) }}
                                                </span>
                                            </td>
                                            <td align="center">
                                                @if ($item->link_whatsapp_estado == 1)
                                                    <span style="cursor: pointer;" wire:click="cargarAlerta({{ $item->id_link_whatsapp }})" class="badge text-bg-success text-light hover-elevate-down fs-6 px-3 py-2">Activo</span>
                                                @else
                                                    <span style="cursor: pointer;" wire:click="cargarAlerta({{ $item->id_link_whatsapp }})" class="badge text-bg-danger text-light hover-elevate-down fs-6 px-3 py-2">Inactivo</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <a class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
                                                    Acciones
                                                    <span class="svg-icon fs-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#modal_links_whatsapp"
                                                        wire:click="cargar_link({{ $item->id_link_whatsapp }})"
                                                        class="menu-link px-3" data-bs-toggle="modal"
                                                        data-bs-target="#modal_links_whatsapp">
                                                            Editar
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        @if ($search != '')
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    No se encontraron resultados para la busqueda
                                                    "{{ $search }}"
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    No hay registros
                                                </td>
                                            </tr>
                                        @endif
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    {{-- paginacion de la tabla --}}
                    @if ($links->hasPages())
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $links->firstItem() }} - {{ $links->lastItem() }} de
                                {{ $links->total() }} registros
                            </div>
                            <div>
                                {{ $links->links() }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $links->firstItem() }} - {{ $links->lastItem() }} de
                                {{ $links->total() }} registros
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Link Whatsapp --}}
    <div wire:ignore.self class="modal fade" id="modal_links_whatsapp" tabindex="-1" aria-labelledby="modal_links_whatsapp"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $titulo }}</h5>
                    <button type="button" wire:click="limpiar_modal()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form novalidate>
                        <div class="row g-5">
                            <div class="col-md-12">
                                <label for="proceso" class="required form-label">
                                    Procesos Académicos
                                </label>
                                <select class="form-select @error('proceso') is-invalid @enderror"
                                    wire:model="proceso" id="proceso" data-control="select2"
                                    data-placeholder="Seleccione su proceso académico" data-allow-clear="true"
                                    data-dropdown-parent="#modal_links_whatsapp">
                                    <option></option>
                                    @foreach ($admisiones as $item)
                                        <option value="{{ $item->id_admision }}">
                                            {{ formatearAdmisionVisual($item->admision) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('proceso')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="modalidad" class="required form-label">
                                    Modalidad
                                </label>
                                <select class="form-select @error('modalidad') is-invalid @enderror"
                                    wire:model="modalidad" id="modalidad" data-control="select2"
                                    data-placeholder="Seleccione su modalidad" data-allow-clear="true"
                                    data-dropdown-parent="#modal_links_whatsapp">
                                    <option></option>
                                    @foreach ($modalidades as $item)
                                        <option value="{{ $item->id_modalidad }}">
                                            {{ $item->id_modalidad == 1 ? 'PRESENCIAL' : 'DISTANCIA' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('modalidad')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="programa_academico" class="required form-label">
                                    Programas Académicos
                                </label>
                                <select class="form-select @error('programa_academico') is-invalid @enderror"
                                    wire:model="programa_academico" id="programa_academico" data-control="select2"
                                    data-placeholder="Seleccione su programa académico" data-allow-clear="true"
                                    data-dropdown-parent="#modal_links_whatsapp">
                                    <option></option>
                                    @foreach ($programas as $item)
                                        <option value="{{ $item->id_programa_proceso }}">
                                            @if ($item->mencion)
                                            {{ $item->programa }} EN {{ $item->subprograma }} MENCION EN {{ $item->mencion }}
                                            @else
                                                {{ $item->programa }} EN {{ $item->subprograma }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('programa_academico')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="link" class="required form-label">
                                    Link de WhatsApp
                                </label>
                                <input type="text" wire:model="link"
                                    class="form-control @error('link') is-invalid @enderror"
                                    placeholder="Ingrese los link, example: https://www.whatsapp.com/url" id="link" />
                                @error('link')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer col-12 d-flex justify-content-between">
                    <button type="button" wire:click="limpiar_modal()" class="btn btn-secondary hover-elevate-up" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click="guardar_link()" class="btn btn-primary hover-elevate-up">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        // proceso select2
        $(document).ready(function() {
            $('#proceso').select2({
                placeholder: 'Seleccione su proceso académico',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#proceso').on('change', function() {
                @this.set('proceso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#proceso').select2({
                    placeholder: 'Seleccione su proceso académico',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#proceso').on('change', function() {
                    @this.set('proceso', this.value);
                });
            });
        });
        // modalidad select2
        $(document).ready(function() {
            $('#modalidad').select2({
                placeholder: 'Seleccione su modalidad',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#modalidad').on('change', function() {
                @this.set('modalidad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#modalidad').select2({
                    placeholder: 'Seleccione su modalidad',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#modalidad').on('change', function() {
                    @this.set('modalidad', this.value);
                });
            });
        });
        // programa_academico select2
        $(document).ready(function() {
            $('#programa_academico').select2({
                placeholder: 'Seleccione su programa académico',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#programa_academico').on('change', function() {
                @this.set('programa_academico', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#programa_academico').select2({
                    placeholder: 'Seleccione su programa académico',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#programa_academico').on('change', function() {
                    @this.set('programa_academico', this.value);
                });
            });
        });
        // proceso_filtro select2
        $(document).ready(function() {
            $('#proceso_filtro').select2({
                placeholder: 'Seleccione su proceso académico',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#proceso_filtro').on('change', function() {
                @this.set('proceso_filtro', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#proceso_filtro').select2({
                    placeholder: 'Seleccione su proceso académico',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#proceso_filtro').on('change', function() {
                    @this.set('proceso_filtro', this.value);
                });
            });
        });
        // modalidad_filtro select2
        $(document).ready(function() {
            $('#modalidad_filtro').select2({
                placeholder: 'Seleccione su modalidad',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#modalidad_filtro').on('change', function() {
                @this.set('modalidad_filtro', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#modalidad_filtro').select2({
                    placeholder: 'Seleccione su modalidad',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#modalidad_filtro').on('change', function() {
                    @this.set('modalidad_filtro', this.value);
                });
            });
        });
    </script>
@endpush
