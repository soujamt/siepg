<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Gestión de Plan y Proceso
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
                            <a href="{{ route('administrador.programa') }}" class="text-muted text-hover-primary">
                                Programa
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Gestión de Plan y Proceso</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalPlanProceso" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalPlanProceso">Nuevo</a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5 fs-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center text-dark fw-bold">
                            {{ $programaModel->programa }} EN  {{ $programaModel->subprograma }}
                            @if ($programaModel->mencion != null)
                                CON MENCION EN {{ $programaModel->mencion }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="me-1">
                                <a href="{{ route('administrador.programa') }}" class="btn btn-secondary btn-sm hover-elevate-up d-flex justify-content-center align-items-center">
                                    <span class="svg-icon svg-icon-muted svg-icon-7">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.6 4L9.6 12L17.6 20H13.6L6.3 12.7C5.9 12.3 5.9 11.7 6.3 11.3L13.6 4H17.6Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                    Regresar
                                </a>
                            </div>

                            <div class="ms-2">
                                <input class="form-control form-control-sm text-muted" type="search" wire:model="search"
                                    placeholder="Buscar...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center" class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col" class="col-md-1">ID</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Plan</th>
                                        <th scope="col" class="col-md-2">Estado</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($programaPlanModel as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_programa_plan }}</td>
                                        <td align="center"> {{ $item->programa_codigo }} </td>
                                        <td align="center"> {{ $item->plan }} </td>                                        
                                        <td align="center">
                                            @if ($item->programa_plan_estado == 1)
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_programa_plan }})" class="badge text-bg-success text-light hover-elevate-down fs-6 px-3 py-2">Activo</span></span>
                                            @else
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_programa_plan }})" class="badge text-bg-danger text-light hover-elevate-down fs-6 px-3 py-2">Inactivo</span></span>
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
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4 w-175px" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="#modalPlanProceso" wire:click="cargarProgramaPlan({{ $item->id_programa_plan }}, 3)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalPlanProceso">
                                                        Detalle
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#modalPlanProceso" wire:click="cargarProgramaPlan({{ $item->id_programa_plan }}, 2)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalPlanProceso">
                                                        Editar
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#modalGestionProcesos" wire:click="cargarProcesos({{ $item->id_programa_plan }}, 4)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalGestionProcesos">
                                                        Gestión de Procesos
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
                        @if ($programaPlanModel->hasPages())
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $programaPlanModel->firstItem() }} - {{ $programaPlanModel->lastItem() }} de
                                    {{ $programaPlanModel->total() }} registros
                                </div>
                                <div>
                                    {{ $programaPlanModel->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $programaPlanModel->firstItem() }} - {{ $programaPlanModel->lastItem() }} de
                                    {{ $programaPlanModel->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> 
    </div>

    {{-- Modal Plan del Programa --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modalPlanProceso">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $titulo }}
                    </h3>
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
                    <form autocomplete="off" class="row g-5 {{ $modo == 3 ? 'mb-3' : '' }}">
                        @if($modo == 3)
                            <div class="col-md-12">
                                <label for="programa_codigo" class="form-label">
                                    Código del Programa
                                </label>
                                <input type="text" wire:model="programa_codigo"
                                    class="form-control"
                                    placeholder="Ingrese el código del programa" id="programa_codigo" readonly/>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <label for="plan" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                Plan
                            </label>
                            @if($modo == 3)
                                <input type="text" wire:model="plan_nombre" class="form-control" id="plan_nombre" readonly />
                            @else
                                <select class="form-select @error('plan') is-invalid @enderror"
                                    wire:model="plan" id="plan" data-control="select2"
                                    data-placeholder="Seleccione el Plan" data-allow-clear="true"
                                    data-dropdown-parent="#modalPlanProceso">
                                    <option></option>
                                    @foreach ($planModel as $item)
                                        <option value="{{ $item->id_plan }}">Plan {{ $item->plan }}</option>
                                    @endforeach
                                </select>
                                @error('plan')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                        @if($modo ==3)
                            <div class="col-md-12">
                                <label  for="programa_plan_creacion" class="form-label">Fecha de creación del plan</label>
                                <input type="text" value="{{ date('d/m/Y, h:i:s A', strtotime($programa_plan_creacion)) }}"
                                    class="form-control" id="programa_plan_creacion" readonly />
                            </div>
                        @endif
                        
                    </form>
                </div>
                @if($modo != 3)
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar()">
                            Cerrar
                        </button>
                        <button type="button" wire:click="guardarProgramaPlan" class="btn btn-primary" style="width: 150px" wire:loading.attr="disabled" wire:target="guardarProgramaPlan">
                            <div wire:loading.remove wire:target="guardarProgramaPlan">
                                Guardar
                            </div>
                            <div wire:loading wire:target="guardarProgramaPlan">
                                Procesando <span class="spinner-border spinner-border-sm align-middle ms-2">
                            </div>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- modal de Procesos del Plan --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modalGestionProcesos">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $titulo }}
                    </h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close"
                        wire:click="limpiar">
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
                    <form autocomplete="off" class="row g-5 mb-3 px-md-5">
                        <div class="col-md-12 mt-5">
                            <div class="row mb-3">
                                <span class="col-12 fw-bold text-gray-800 fs-3">
                                    INFORMACIÓN DEL PLAN DEL PROGRAMA
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Programa
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $nombrePrograma }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Código del Programa
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $programa_codigo }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Plan
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $plan_nombre }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Fecha de Creación del Plan
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ date('d/m/Y, h:i:s A', strtotime($programa_plan_creacion)) }}
                                </span>
                            </div>
                        </div>
                        <div class="separator separator-dotted separator-content border-primary mb-5 mt-7">
                            <i class="ki-duotone ki-plus-square fs-1 text-primary">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                            </i>
                        </div>
                        <div class="col-md-12">
                            <label for="proceso_admision" class="required form-label fw-semibold text-gray-600 fs-5">
                                Proceso de Admisión
                            </label>
                            <select class="form-select @error('proceso_admision') is-invalid @enderror"
                                wire:model="proceso_admision" id="proceso_admision" data-control="select2"
                                data-placeholder="Seleccione de Proceso de Admisión" data-allow-clear="true"
                                data-dropdown-parent="#modalGestionProcesos">
                                <option></option>
                                @foreach ($admisionModel as $item)
                                    <option value="{{ $item->id_admision }}">
                                        {{ formatearAdmisionVisual($item->admision) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proceso_admision')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="text-end">
                                <button type="button" wire:click="limpiarProcesos" class="btn fw-bold fs-5 btn-light">
                                    Cancelar
                                </button>
                                <button type="button" wire:click="asignarProceso" class="btn fw-bold fs-5 btn-primary" wire:loading.attr="disabled" wire:target="asignarProceso">
                                    <div wire:loading.remove wire:target="asignarProceso, voucher">
                                        Asignar Proceso
                                    </div>
                                    <div wire:loading wire:target="asignarProceso">
                                        Asignando <span class="spinner-border spinner-border-sm align-middle ms-2">
                                    </div>
                                </button>
                            </div>
                        </div>
                        <span class="col-12 fw-bold text-gray-700 fs-3">
                            PROCESOS ASIGNADOS
                        </span>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                <thead class="bg-light-primary">
                                    <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                        <th scope="col">ID</th>
                                        <th scope="col">Proceso de Admisión</th>
                                        <th scope="col">Estado de Admisión</th>
                                        <th scope="col" class="col-md-2">Estado</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                @php
                                    $id_proceso = 0;
                                @endphp
                                <tbody class="fw-semibold text-gray-700">
                                    @if ($programaProceso)
                                        @forelse ($programaProceso as $item)
                                            <tr>
                                                <td class="fw-bold fs-6">
                                                    {{ $id_proceso += 1 }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ formatearAdmisionVisual($item->admision->admision) }}
                                                </td>
                                                <td class="fs-6">
                                                    @if ($item->admision->admision_estado == 1)
                                                        <span class="badge badge-light-success fs-6 px-3 py-2">
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="badge badge-light-danger fs-6 px-3 py-2">
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="fs-6">
                                                    @if ($item->programa_proceso_estado == 1)
                                                        <span class="badge badge-success fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado_proceso({{ $item->id_programa_proceso }})"
                                                            style="cursor: pointer;">
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado_proceso({{ $item->id_programa_proceso }})"
                                                            style="cursor: pointer;">
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="fs-6">
                                                    <button type="button"
                                                        class="btn btn-flex btn-center fw-bold btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary"
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
                                                    <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-175px py-4"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a style="cursor: pointer;"
                                                                wire:click="alerta_eliminar_proceso({{ $item->id_programa_proceso }})"
                                                                class="menu-link px-3 fs-6">
                                                                Eliminar Proceso
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    No hay registros
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

</div>

@push('scripts')
<script>
    // plan select2
    $(document).ready(function () {
        $('#plan').select2({
            placeholder: 'Seleccione',
            allowClear: true,
            width: '100%',
            selectOnClose: true,
            language: {
                noResults: function () {
                    return "No se encontraron resultados";
                },
                searching: function () {
                    return "Buscando..";
                }
            }
        });
        $('#plan').on('change', function(){
            @this.set('plan', this.value);
        });
        Livewire.hook('message.processed', (message, component) => {
            $('#plan').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#plan').on('change', function(){
                @this.set('plan', this.value);
            });
        });
    });

    // proceso_admision select2
    $(document).ready(function () {
        $('#proceso_admision').select2({
            placeholder: 'Seleccione de Proceso de Admisión',
            allowClear: true,
            width: '100%',
            selectOnClose: true,
            language: {
                noResults: function () {
                    return "No se encontraron resultados";
                },
                searching: function () {
                    return "Buscando..";
                }
            }
        });
        $('#proceso_admision').on('change', function(){
            @this.set('proceso_admision', this.value);
        });
        Livewire.hook('message.processed', (message, component) => {
            $('#proceso_admision').select2({
                placeholder: 'Seleccione de Proceso de Admisión',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#proceso_admision').on('change', function(){
                @this.set('proceso_admision', this.value);
            });
        });
    });
</script>
    
@endpush