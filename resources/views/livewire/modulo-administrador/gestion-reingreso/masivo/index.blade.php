<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Gestión de Reingreso - Masivo
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Gestión de Reingreso - Masivo
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    <button type="button" class="btn btn-primary fw-bold" wire:click="modo" data-bs-toggle="modal"
                        data-bs-target="#modal_reingreso">
                        Nuevo Reingreso
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 d-flex align-items-center">
                            <i class="las la-exclamation-circle fs-1 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold fs-5">
                                A continuación se muestra la lista de reingresos masivos.
                            </span>
                        </div>
                    </div>
                    {{-- card la tabla --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            {{-- header de la tabla --}}
                            <div class="d-flex flex-column flex-md-row align-items-center mb-5 w-100">
                                <div class="col-md-4 pe-md-3 mb-2 mb-md-0">
                                    {{-- <button type="button" class="btn btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-center fw-bold w-100px w-md-125px"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                        <span class="svg-icon svg-icon-3 me-1">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                    fill="currentColor" />
                                            </svg>
                                        </span>
                                        Filtrar
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                        data-kt-menu="true" id="filtros_docentes" wire:ignore.self>
                                        <div class="px-7 py-5">
                                            <div class="fs-4 text-dark fw-bold">
                                                Opciones de Filtro
                                            </div>
                                        </div>
                                        <div class="separator border-gray-200"></div>
                                        <form class="px-7 py-5" wire:submit.prevent="aplicar_filtro">
                                            <div class="mb-5">
                                                <label class="form-label fw-semibold">
                                                    Grado Académico:
                                                </label>
                                                <div>
                                                    <select class="form-select" wire:model="filtro_grado_academico"
                                                        id="filtro_grado_academico" data-control="select2"
                                                        data-placeholder="Seleccione su grado académico">
                                                        @foreach ($grados_academicos as $item)
                                                            <option value=""></option>
                                                            <option value="{{ $item->id_grado_academico }}">
                                                                {{ $item->grado_academico }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-10">
                                                <label class="form-label fw-semibold">
                                                    Tipo de Docente:
                                                </label>
                                                <div>
                                                    <select class="form-select" wire:model="filtro_tipo_docente"
                                                        id="filtro_tipo_docente" data-control="select2"
                                                        data-placeholder="Seleccione el tipo de docente">
                                                        @foreach ($tipos_docentes as $item)
                                                            <option value=""></option>
                                                            <option value="{{ $item->id_tipo_docente }}">
                                                                {{ $item->tipo_docente }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" wire:click="resetear_filtro"
                                                    class="btn btn-light btn-active-light-primary me-2"
                                                    data-kt-menu-dismiss="true">Resetear</button>
                                                <button type="submit" class="btn btn-primary"
                                                    data-kt-menu-dismiss="true">Aplicar</button>
                                            </div>
                                        </form>
                                    </div> --}}
                                </div>
                                <div class="col-md-4 px-md-3 mb-2 mb-md-0"></div>
                                <div class="col-md-4 ps-md-3">
                                    <input type="search" wire:model="search" class="form-control w-100"
                                        placeholder="Buscar..." />
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                            <th>#</th>
                                            <th>Codigo Reingreso</th>
                                            <th>Codigo Alumno</th>
                                            <th>Apellidos y Nombres</th>
                                            <th>Programa</th>
                                            <th>Proceso</th>
                                            <th>Fecha</th>
                                            {{-- <th></th> --}}
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-700">
                                        @forelse ($reingresos as $item)
                                            <tr>
                                                <td class="fw-bold fs-6">
                                                    {{ $item->id_reingreso }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->reingreso_codigo }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->admitido_codigo }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->apellido_paterno }}
                                                    {{ $item->apellido_materno }},
                                                    {{ $item->nombre }}
                                                </td>
                                                <td class="fs-6">
                                                    @if ($item->mencion)
                                                        {{ $item->programa }} EN {{ $item->subprograma }} CON MENCION EN {{ $item->admitido->mencion }}
                                                    @else
                                                        {{ $item->programa }} EN {{ $item->subprograma }}
                                                    @endif
                                                </td>
                                                <td class="">
                                                    <span class="badge badge-light-info fs-6 px-3 py-2 my-1">
                                                        {{ formatearAdmisionVisual($item->programa_proceso->admision->admision) }}
                                                    </span>
                                                    <span class="badge badge-light-secondary fs-6 px-3 py-2 text-gray-700 my-1">
                                                        PLAN {{ $item->programa_proceso->programa_plan->plan->plan }}
                                                    </span>
                                                </td>
                                                <td class="fs-6">
                                                    {{ date('d/m/Y h:i A', strtotime($item->reingreso_fecha_creacion)) }}
                                                </td>
                                                {{-- <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-flex btn-center fw-bold btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                        data-bs-toggle="dropdown">
                                                        Acciones
                                                        <span class="svg-icon fs-5 rotate-180 ms-2 me-0 m-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                width="24px" height="24px" viewBox="0 0 24 24"
                                                                version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                    <path
                                                                        d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z"
                                                                        fill="currentColor" fill-rule="nonzero"
                                                                        transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)">
                                                                    </path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-150px py-4"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a href="#modal_docente_detalle"
                                                                wire:click="cargar_docente({{ $item->id_docente }}, 'show')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_docente_detalle">
                                                                Detalle
                                                            </a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a href="#modal_docente"
                                                                wire:click="cargar_docente({{ $item->id_docente }}, 'edit')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_docente">
                                                                Editar Datos
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td> --}}
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda
                                                        "{{ $search }}"
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        No hay registros
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{-- paginacion de la tabla --}}
                            @if ($reingresos->hasPages())
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $reingresos->firstItem() }} - {{ $reingresos->lastItem() }} de
                                        {{ $reingresos->total() }} registros
                                    </div>
                                    <div>
                                        {{ $reingresos->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $reingresos->firstItem() }} - {{ $reingresos->lastItem() }} de
                                        {{ $reingresos->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_reingreso">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $title_modal }}
                    </h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                    rx="5" fill="currentColor" />
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" class="row g-5">
                        <div class="col-lg-6">
                            <input type="radio" class="btn-check" wire:model="check_cambio_plan" value="1" id="reingreso_option_1"/>
                            <label class="btn btn-outline btn-active-light-success p-7 d-flex align-items-center" for="reingreso_option_1">
                                <i class="ki-duotone ki-shield-slash fs-3x me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <span class="d-block fw-semibold text-start">
                                    <span class="text-dark fw-bold d-block fs-3">
                                        Reingreso sin cambio de Plan
                                    </span>
                                    <span class="text-muted fw-semibold fs-6">
                                        Al seleccionar esta opción, los alumnos reingresaran al mismo plan académico en el que se encuentra actualmente.
                                    </span>
                                </span>
                            </label>
                        </div>
                        <div class="col-lg-6">
                            <input type="radio" class="btn-check" wire:model="check_cambio_plan" disabled value="2" id="reingreso_option_2"/>
                            <label class="btn btn-outline btn-active-light-success p-7 d-flex align-items-center" for="reingreso_option_2">
                                <i class="ki-duotone ki-shield-tick fs-3x me-4"><span class="path1"></span><span class="path2"></span></i>
                                <span class="d-block fw-semibold text-start">
                                    <span class="text-dark fw-bold d-block fs-3">
                                        Reingreso con cambio de Plan
                                    </span>
                                    <span class="text-muted fw-semibold fs-6">
                                        Al seleccionar esta opción, los alumnos reingresaran a un nuevo plan académico.
                                    </span>
                                </span>
                            </label>
                        </div>
                        @if ($check_cambio_plan == 1 || $check_cambio_plan == 2)
                        <div class="col-md-12">
                            <label for="proceso" class="required form-label">
                                Proceso Académico
                            </label>
                            <select class="form-select @error('proceso') is-invalid @enderror"
                                wire:model="proceso" id="proceso" data-control="select2"
                                data-placeholder="Seleccione su proceso académico" data-allow-clear="true"
                                data-dropdown-parent="#modal_reingreso">
                                <option></option>
                                @foreach ($procesos as $item)
                                    <option value="{{ $item->id_admision }}">
                                        PROCESO DE {{ formatearAdmisionVisual($item->admision) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proceso')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="programa" class="required form-label">
                                Porgrama Académico
                            </label>
                            <select class="form-select @error('programa') is-invalid @enderror"
                                wire:model="programa" id="programa" data-control="select2"
                                data-placeholder="Seleccione su programa académico" data-allow-clear="true"
                                data-dropdown-parent="#modal_reingreso">
                                <option></option>
                                @foreach ($programas as $item)
                                    <option value="{{ $item->id_programa_proceso }}">
                                        @if ($item->mencion)
                                            {{ $item->programa }} EN {{ $item->subprograma }} CON MENCION EN
                                            {{ $item->mencion }}
                                        @else
                                            {{ $item->programa }} EN {{ $item->subprograma }}
                                        @endif
                                        {{ $item->id_modalidad == 1 ? ' - PRESENCIAL' : ' - DISTANCIA' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('programa')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                        @if ($check_cambio_plan == 2)
                        <div class="col-md-12">
                            <div class="separator separator-content my-3 text-gray-500">x</div>
                        </div>
                        <div class="col-md-12">
                            <label for="plan_nuevo" class="required form-label">
                                Plan Académico al que desea reingresar
                            </label>
                            <select class="form-select @error('plan_nuevo') is-invalid @enderror"
                                wire:model="plan_nuevo" id="plan_nuevo" data-control="select2"
                                data-placeholder="Seleccione su plan académico" data-allow-clear="true"
                                data-dropdown-parent="#modal_reingreso">
                                <option></option>
                                @foreach ($planes as $item)
                                    <option value="{{ $item->id_plan }}">
                                        PLAN {{ $item->plan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_nuevo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="programa_reingreso" class="required form-label">
                                Porgrama Académico al que desea reingresar
                            </label>
                            <select class="form-select @error('programa_reingreso') is-invalid @enderror"
                                wire:model="programa_reingreso" id="programa_reingreso" data-control="select2"
                                data-placeholder="Seleccione su programa académico" data-allow-clear="true"
                                data-dropdown-parent="#modal_reingreso">
                                <option></option>
                                @foreach ($programas_reingreso as $item)
                                    <option value="{{ $item->id_programa_proceso }}">
                                        @if ($item->mencion)
                                            {{ $item->programa }} EN {{ $item->subprograma }} CON MENCION EN
                                            {{ $item->mencion }}
                                        @else
                                            {{ $item->programa }} EN {{ $item->subprograma }}
                                        @endif
                                        {{ $item->id_modalidad == 1 ? ' - PRESENCIAL' : ' - DISTANCIA' }} - {{ ' PROCESO DE ' . formatearAdmisionVisual($item->admision) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('programa_reingreso')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                        <div class="col-md-12">
                            <label for="resolucion" class="required form-label">
                                Nombre de Resolución
                            </label>
                            <input type="text" wire:model="resolucion"
                                class="form-control @error('resolucion') is-invalid @enderror"
                                placeholder="Ingrese el nombre de la resolucion" id="resolucion" />
                            @error('resolucion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="resolucion_file"
                                class="form-label">
                                Resolución
                            </label>
                            <input type="file" wire:model="resolucion_file"
                                class="form-control @error('resolucion_file') is-invalid @enderror"
                                id="resolucion_file" accept="application/pdf" />
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: La resolución debe estar en formato PDF. El tamaño máximo es de 10MB. <br>
                            </span>
                            @error('resolucion_file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="limpiar_modal">Cerrar</button>
                    <button type="button" wire:click="guardar_reingreso" class="btn btn-primary" style="width: 150px"
                        wire:loading.attr="disabled" wire:target="guardar_reingreso">
                        <div wire:loading.remove wire:target="guardar_reingreso">
                            Guardar
                        </div>
                        <div wire:loading wire:target="guardar_reingreso">
                            Procesando <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // programa select2
        $(document).ready(function() {
            $('#programa').select2({
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
            $('#programa').on('change', function() {
                @this.set('programa', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#programa').select2({
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
                $('#programa').on('change', function() {
                    @this.set('programa', this.value);
                });
            });
        });
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
        // plan_nuevo select2
        $(document).ready(function() {
            $('#plan_nuevo').select2({
                placeholder: 'Seleccione su plan',
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
            $('#plan_nuevo').on('change', function() {
                @this.set('plan_nuevo', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#plan_nuevo').select2({
                    placeholder: 'Seleccione su plan',
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
                $('#plan_nuevo').on('change', function() {
                    @this.set('plan_nuevo', this.value);
                });
            });
        });
        // proceso_nuevo select2
        $(document).ready(function() {
            $('#proceso_nuevo').select2({
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
            $('#proceso_nuevo').on('change', function() {
                @this.set('proceso_nuevo', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#proceso_nuevo').select2({
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
                $('#proceso_nuevo').on('change', function() {
                    @this.set('proceso_nuevo', this.value);
                });
            });
        });
        // programa_reingreso select2
        $(document).ready(function() {
            $('#programa_reingreso').select2({
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
            $('#programa_reingreso').on('change', function() {
                @this.set('programa_reingreso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#programa_reingreso').select2({
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
                $('#programa_reingreso').on('change', function() {
                    @this.set('programa_reingreso', this.value);
                });
            });
        });
    </script>
@endpush
