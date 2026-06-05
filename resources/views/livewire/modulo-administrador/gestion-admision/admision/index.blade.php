<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Admision
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
                        <li class="breadcrumb-item text-muted">Admision</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalAdmision" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up"
                        data-bs-toggle="modal" data-bs-target="#modalAdmision">Nuevo</a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="d-flex justify-content-between align-items-center gap-4">
                            </div>
                            <div class="w-25">
                                <input class="form-control form-control-sm text-muted" type="search"
                                    wire:model="search" placeholder="Buscar...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center"
                                        class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col" class="col-md-1">ID</th>
                                        <th scope="col" class="col-md-3">Admisión</th>
                                        <th scope="col" class="col-md-2">Año</th>
                                        <th scope="col" class="col-md-2">Estado</th>
                                        <th scope="col" class="col-md-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($admision_model as $item)
                                        <tr>
                                            <td align="center" class="fw-bold fs-5">{{ $item->id_admision }}</td>
                                            <td align="center">{{ formatearAdmisionVisual($item->admision) }}</td>
                                            <td align="center">{{ $item->admision_año }}</td>
                                            <td align="center">
                                                @if ($item->admision_estado == 1)
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item->id_admision }})"
                                                        class="badge text-bg-success text-light hover-elevate-down fs-6 px-3 py-2">Activo</span>
                                                @else
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item->id_admision }})"
                                                        class="badge text-bg-danger text-light hover-elevate-down fs-6 px-3 py-2">Inactivo</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <a class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm"
                                                    data-bs-toggle="dropdown">
                                                    Acciones
                                                    <span class="svg-icon fs-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1">
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
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
                                                    data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#modalAdmision"
                                                            wire:click="cargarAdmision({{ $item->id_admision }})"
                                                            class="menu-link px-3" data-bs-toggle="modal"
                                                            data-bs-target="#modalAdmision">
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
                        @if ($admision_model->hasPages())
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $admision_model->firstItem() }} - {{ $admision_model->lastItem() }} de
                                    {{ $admision_model->total() }} registros
                                </div>
                                <div>
                                    {{ $admision_model->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $admision_model->firstItem() }} - {{ $admision_model->lastItem() }}
                                    de
                                    {{ $admision_model->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Usuario --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modalAdmision">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $titulo }}
                    </h2>
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                    fill="currentColor" />
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="row">
                            <div class="mb-3 col-md-6 col-sm-6">
                                <label class="form-label">Año <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('año') is-invalid  @enderror"
                                    wire:model="año" placeholder="Ingrese el año del proceso de admision">
                                @error('año')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Convocatoria </label>
                                <input type="text"
                                    class="form-control @error('convocatoria') is-invalid  @enderror"
                                    wire:model="convocatoria"
                                    placeholder="Ingrese la convocatoria del proceso de admision">
                                @error('convocatoria')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de inicio de admisión <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_inicio_inscripcion') is-invalid  @enderror"
                                    wire:model="fecha_inicio_inscripcion">
                                @error('fecha_inicio_inscripcion')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de fin de admisión <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_fin_inscripcion') is-invalid  @enderror"
                                    wire:model="fecha_fin_inscripcion">
                                @error('fecha_fin_inscripcion')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de inicio de expediente <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_inicio_expediente') is-invalid  @enderror"
                                    wire:model="fecha_inicio_expediente">
                                @error('fecha_inicio_expediente')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de fin de expediente <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_fin_expediente') is-invalid  @enderror"
                                    wire:model="fecha_fin_expediente">
                                @error('fecha_fin_expediente')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de inicio de entrevista <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_inicio_entrevista') is-invalid  @enderror"
                                    wire:model="fecha_inicio_entrevista">
                                @error('fecha_inicio_entrevista')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de fin de entrevista <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_fin_entrevista') is-invalid  @enderror"
                                    wire:model="fecha_fin_entrevista">
                                @error('fecha_fin_entrevista')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Fecha de resultados <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_resultados') is-invalid  @enderror"
                                    wire:model="fecha_resultados">
                                @error('fecha_resultados')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de inicio de matrícula <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_inicio_matricula') is-invalid  @enderror"
                                    wire:model="fecha_inicio_matricula">
                                @error('fecha_inicio_matricula')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de fin de matrícula <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_fin_matricula') is-invalid  @enderror"
                                    wire:model="fecha_fin_matricula">
                                @error('fecha_fin_matricula')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de inicio de matricula extemporánea <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_inicio_extemporanea') is-invalid  @enderror"
                                    wire:model="fecha_inicio_extemporanea">
                                @error('fecha_inicio_extemporanea')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Fecha de fin de matricula extemporánea <span
                                        class="text-danger">*</span></label>
                                <input type="date"
                                    class="form-control @error('fecha_fin_extemporanea') is-invalid  @enderror"
                                    wire:model="fecha_fin_extemporanea">
                                @error('fecha_fin_extemporanea')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer col-12 d-flex justify-content-between">
                    <button type="button" wire:click="limpiar()" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click="guardarAdmision()" class="btn btn-primary"
                        wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target="guardarAdmision">
                            Guardar
                        </div>
                        <div wire:loading wire:target="guardarAdmision">
                            <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            Guardando...
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
