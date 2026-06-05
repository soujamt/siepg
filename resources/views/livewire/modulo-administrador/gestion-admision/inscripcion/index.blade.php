<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Inscripción
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
                        <li class="breadcrumb-item text-muted">Inscripción</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    <button type="button" class="btn btn-primary btn-sm hover-elevate-up" data-kt-menu-trigger="click"
                        data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-muted svg-icon-3"><svg width="24" height="24"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor" />
                                <path opacity="0.3" d="M13 14.4V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V14.4H13Z"
                                    fill="currentColor" />
                                <path
                                    d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM13 14.4V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V14.4H8L11.3 17.7C11.7 18.1 12.3 18.1 12.7 17.7L16 14.4H13Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        Exportar
                    </button>
                    <div id="kt_datatable_example_export_menu"
                        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                        data-kt-menu="true">
                        <div class="menu-item px-3">
                            <a href="#exportarExcel" wire:click="excel()" class="menu-link px-3">
                                Exportar a Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-1 d-flex gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    mostrar
                                    <select class="form-select form-select-sm" wire:model="cant_paginas">
                                        <option value="10">
                                            10
                                        </option>
                                        <option value="25">
                                            25
                                        </option>
                                        <option value="50">
                                            50
                                        </option>
                                        <option value="100">
                                            100
                                        </option>
                                        <option value="150">
                                            150
                                        </option>
                                        <option value="200">
                                            200
                                        </option>
                                    </select>
                                    registros
                                </div>
                                <a class="btn btn-sm btn-light-primary me-3 fw-bold" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-start">
                                    <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    Filtro
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-500px" data-kt-menu="true"
                                    id="menu_inscripcion" wire:ignore.self>
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">
                                            Opciones de filtrado
                                        </div>
                                    </div>
                                    <div class="separator border-gray-200"></div>
                                    <div class="px-7 py-5 row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Proceso de Admisión:</label>
                                            <div>
                                                <select class="form-select" wire:model="proceso_filtro"
                                                    id="proceso_filtro" data-control="select2"
                                                    data-placeholder="Seleccione el Proceso">
                                                    <option></option>
                                                    @foreach ($procesos as $item)
                                                        <option value="{{ $item->id_admision }}">{{ formatearAdmisionVisual($item->admision) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Modalidad del Programa:</label>
                                            <div>
                                                <select class="form-select" wire:model="modalidad_filtro"
                                                    id="modalidad_filtro" data-control="select2"
                                                    data-placeholder="Seleccione la Modalidad">
                                                    <option></option>
                                                    @foreach ($modalidades as $item)
                                                        <option value="{{ $item->id_modalidad }}">{{ $item->modalidad }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label fw-semibold">Programa:</label>
                                            <div>
                                                <select class="form-select" wire:model="programa_filtro"
                                                    id="programa_filtro" data-control="select2"
                                                    data-placeholder="Seleccione el Programa">
                                                    <option></option>
                                                    @if ($modalidad_filtro)
                                                        @php
                                                            $programas = App\Models\Programa::where(
                                                                'id_modalidad',
                                                                $modalidad_filtro,
                                                            )->get();
                                                        @endphp
                                                        @foreach ($programas as $item)
                                                            <option value="{{ $item->id_programa }}">
                                                                {{ $item->programa }} EN {{ $item->subprograma }}
                                                                @if ($item->mencion != '')
                                                                    CON MENCION EN {{ $item->mencion }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Tipo de Seguimiento:</label>
                                            <div>
                                                <select class="form-select" wire:model="seguimiento_filtro"
                                                    id="seguimiento_filtro" data-control="select2"
                                                    data-placeholder="Seleccione el Seguimiento">
                                                    <option></option>
                                                    @foreach ($seguimientos as $item)
                                                        <option value="{{ $item->id_tipo_seguimiento }}">
                                                            {{ $item->tipo_seguimiento }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Mes:</label>
                                            <div>
                                                <select class="form-select" wire:model="mes_filtro" id="mes_filtro"
                                                    data-control="select2" data-placeholder="Seleccione el Mes">
                                                    <option></option>
                                                    @if ($proceso_filtro)
                                                        @php
                                                            $anioAdmision = App\Models\Admision::where(
                                                                'id_admision',
                                                                $proceso_filtro,
                                                            )->first();
                                                        @endphp
                                                        @foreach ($mesesUnicos as $item)
                                                            @if ($item->anio == $anioAdmision->admision_año)
                                                                <option value="{{ $item->mes }}">
                                                                    {{ $meses[$item->mes] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Estado:</label>
                                            <div>
                                                <select class="form-select" wire:model="estado_filtro">
                                                    <option value="">
                                                        Seleccione un estado...
                                                    </option>
                                                    <option value="0">
                                                        Pendiente
                                                    </option>
                                                    <option value="1">
                                                        Verificado
                                                    </option>
                                                    <option value="2">
                                                        Observado
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="button" wire:click="resetear_filtro"
                                                class="btn btn-sm btn-light btn-active-light-primary me-2"
                                                data-kt-menu-dismiss="true">Resetear</button>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                data-kt-menu-dismiss="true" wire:click="filtrar">Aplicar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ms-2">
                                <div class="d-flex gap-3">
                                    <select class="form-select form-select-sm" style="width: 300px;"
                                        wire:model="estado_expediente_filtro">
                                        <option value="all">
                                            Seleccione un estado de expediente...
                                        </option>
                                        <option value="0">
                                            Pendiente
                                        </option>
                                        <option value="1">
                                            Verificado
                                        </option>
                                        <option value="2">
                                            Observado
                                        </option>
                                    </select>
                                    <input class="form-control form-control-sm text-muted" type="search"
                                        wire:model="search" placeholder="Buscar...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center"
                                        class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col">ID</th>
                                        <th scope="col">Postulante</th>
                                        <th scope="col">Programa</th>
                                        <th scope="col" class="col-md-1">Tipo</th>
                                        <th scope="col" class="col-md-1">Modalidad</th>
                                        <th scope="col" class="col-md-1">Fecha</th>
                                        <th scope="col" class="col-md-1">Verificación</th>
                                        <th scope="col" class="col-md-1">Expedientes</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inscripcionModel as $item)
                                        <tr wire:key="{{ $item->id_inscripcion }}" class="{{ $item->retiro_inscripcion == 1 ? 'bg-light-warning' : '' }}">
                                            <td align="center" class="fw-bold">
                                                {{ $item->id_inscripcion }}
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-900 text-hover-primary mb-1">
                                                        {{ $item->apellido_paterno }} {{ $item->apellido_materno }},
                                                        {{ $item->nombre }}
                                                    </span>
                                                    <span class="text-gray-600">{{ $item->numero_documento }}</span>
                                                    <span class="text-gray-600">Cel. {{ $item->celular }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $item->programa }}
                                                EN {{ $item->subprograma }}
                                                @if ($item->mencion != '')
                                                    CON MENCION EN {{ $item->mencion }}
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if ($item->es_traslado_externo == 1)
                                                    <span
                                                        class="badge badge-light-warning text-dark fs-6 px-3 py-2 text-uppercase">
                                                        Traslado Externo
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge badge-light-primary text-dark fs-6 px-3 py-2 text-uppercase">
                                                        Inscripción Regular
                                                    </span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <span class="badge badge-light-primary fs-6 px-3 py-2">
                                                    {{ $item->modalidad }}
                                                </span>
                                            </td>
                                            <td align="center">
                                                {{ date('d/m/Y', strtotime($item->inscripcion_fecha)) }}
                                            </td>
                                            <td align="center">
                                                @if ($item->inscripcion_estado == 1)
                                                    <span class="badge badge-success fs-6 px-3 py-2">
                                                        Verificado
                                                    </span>
                                                @elseif($item->inscripcion_estado == 2)
                                                    <span class="badge badge-danger fs-6 px-3 py-2">
                                                        Anulado
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning fs-6 px-3 py-2">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if ($item->verificar_expedientes == 1)
                                                    <span class="badge badge-success badge-outline fs-6 px-3 py-2">
                                                        Verificado
                                                    </span>
                                                @elseif($item->verificar_expedientes == 2)
                                                    <span class="badge badge-danger badge-outline fs-6 px-3 py-2">
                                                        Observado
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning badge-outline  fs-6 px-3 py-2">
                                                        Pendiente
                                                    </span>
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
                                                <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4 w-175px"
                                                    data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#modal-expediente"
                                                            wire:click="cargar_expedientes({{ $item->id_inscripcion }}, 3)"
                                                            class="menu-link px-3" data-bs-toggle="modal"
                                                            data-bs-target="#modal-expediente">
                                                            Ver Expedientes
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#ModalInscripcionEditar"
                                                            wire:click="cargarInscripcion({{ $item->id_inscripcion }}, 2)"
                                                            class="menu-link px-3" data-bs-toggle="modal"
                                                            data-bs-target="#ModalInscripcionEditar">
                                                            Editar Programa
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#modal-estado-inscripcion"
                                                            wire:click="cargar_inscripcion({{ $item->id_inscripcion }})"
                                                            class="menu-link px-3" data-bs-toggle="modal"
                                                            data-bs-target="#modal-estado-inscripcion">
                                                            Editar Estado de Inscripción
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a wire:click="actualizar_ficha_inscripcion({{ $item->id_inscripcion }})"
                                                            class="menu-link px-3 cursor-pointer">
                                                            Actualizar Ficha de Inscripción
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a style="cursor: pointer"
                                                            wire:click="reservar_inscripcion({{ $item->id_inscripcion }})"
                                                            class="menu-link px-3">
                                                            Reservar Inscripción
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a style="cursor: pointer"
                                                            wire:click="eliminar_inscripcion({{ $item->id_inscripcion }})"
                                                            class="menu-link px-3">
                                                            Eliminar Inscripción
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        @if ($search != '')
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">
                                                    No se encontraron resultados para la busqueda
                                                    "{{ $search }}"
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">
                                                    No hay registros
                                                </td>
                                            </tr>
                                        @endif
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{-- paginacion de la tabla --}}
                        @if ($inscripcionModel->hasPages())
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $inscripcionModel->firstItem() }} -
                                    {{ $inscripcionModel->lastItem() }} de
                                    {{ $inscripcionModel->total() }} registros
                                </div>
                                <div>
                                    {{ $inscripcionModel->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $inscripcionModel->firstItem() }} -
                                    {{ $inscripcionModel->lastItem() }} de
                                    {{ $inscripcionModel->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Expedientes --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal-expediente">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Expedientes de Inscripción
                    </h3>
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
                <div class="modal-body row g-5">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center"
                                        class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col">ID</th>
                                        <th scope="col">Expediente</th>
                                        <th scope="col" class="col-md-1"></th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col" class="col-md-1">Verificación</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expedientes as $item)
                                        <tr>
                                            <td align="center" class="fw-bold">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $item->expediente_admision->expediente->expediente }}
                                            </td>
                                            <td>
                                                <a href="{{ asset($item->expediente_inscripcion_url) }}"
                                                    target="_blank" class="btn btn-sm btn-dark">
                                                    Ver
                                                </a>
                                            </td>
                                            <td align="center">
                                                {{ convertirFechaHora($item->expediente_inscripcion_fecha) }}
                                            </td>
                                            <td align="center">
                                                @if ($item->expediente_inscripcion_verificacion == 1)
                                                    <span class="badge badge-light-success fs-6 px-3 py-2">
                                                        Verificado
                                                    </span>
                                                @elseif($item->expediente_inscripcion_verificacion == 2)
                                                    <span class="badge badge-light-danger fs-6 px-3 py-2">
                                                        Rechazado
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-warning fs-6 px-3 py-2">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="verificar_expediente({{ $item->id_expediente_inscripcion }})">
                                                    Verificar
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="rechazar_expediente({{ $item->id_expediente_inscripcion }})">
                                                    Rechazar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Editar Inscripcion --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="ModalInscripcionEditar">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Actualizar Programa
                    </h3>
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
                    <form autocomplete="off" class="row g-5">
                        <div class="col-md-12">
                            <label for="modalidad" class="form-label">
                                Modalidad
                            </label>
                            <select class="form-select @error('modalidad') is-invalid @enderror"
                                wire:model="modalidad" id="modalidad" data-control="select2"
                                data-dropdown-parent="#ModalInscripcionEditar"
                                data-placeholder="Seleccione la Modalidad">
                                <option></option>
                                @foreach ($modalidadesModal as $item)
                                    @php
                                        $modalidadAsignadas = App\Models\Modalidad::where(
                                            'id_modalidad',
                                            $item,
                                        )->first();
                                    @endphp
                                    <option value="{{ $item }}">{{ $modalidadAsignadas->modalidad }}</option>
                                @endforeach
                            </select>
                            @error('modalidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="programa" class="form-label">
                                Programa
                            </label>
                            <select class="form-select @error('programa') is-invalid @enderror" wire:model="programa"
                                data-dropdown-parent="#ModalInscripcionEditar" id="programa" data-control="select2"
                                data-placeholder="Seleccione el Programa">
                                <option></option>
                                @foreach ($programasModal as $item)
                                    <option value="{{ $item->id_programa }}">{{ $item->programa }} EN
                                        {{ $item->subprograma }} @if ($item->mencion != '')
                                            CON MENCION EN {{ $item->mencion }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('programa')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar()">
                        Cerrar
                    </button>
                    <button type="button" wire:click="actualizarInscripcion" class="btn btn-primary"
                        style="width: 150px" wire:loading.attr="disabled" wire:target="actualizarInscripcion">
                        <div wire:loading.remove wire:target="actualizarInscripcion">
                            Guardar
                        </div>
                        <div wire:loading wire:target="actualizarInscripcion">
                            Procesando <span class="spinner-border spinner-border-sm align-middle ms-2">
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Editar Estado de Inscripcion --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal-estado-inscripcion">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Actualizar Estado de Inscripción
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" wire:click="limpiar()"
                        data-bs-dismiss="modal" aria-label="Close">
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
                    <form autocomplete="off" class="row g-5">
                        <div class="col-md-12">
                            <label for="estado" class="form-label">
                                Estado
                            </label>
                            <select class="form-select @error('estado') is-invalid @enderror" wire:model="estado"
                                id="estado">
                                <option value="">Seleccione un estado...</option>
                                <option value="0">
                                    PENDIENTE
                                </option>
                                <option value="1">
                                    VERIFICAR
                                </option>
                                <option value="2">
                                    RECHAZAR
                                </option>
                            </select>
                            @error('estado')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="observacion_inscripcion" class="form-label">
                                Observación
                            </label>
                            <textarea class="form-control @error('observacion_inscripcion') is-invalid @enderror"
                                wire:model="observacion_inscripcion" id="observacion_inscripcion" rows="3">
                            </textarea>
                            @error('observacion_inscripcion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar()">
                        Cerrar
                    </button>
                    <button type="button" wire:click="editar_estado" class="btn btn-primary" style="width: 150px"
                        wire:loading.attr="disabled" wire:target="editar_estado">
                        <div wire:loading.remove wire:target="editar_estado">
                            Guardar
                        </div>
                        <div wire:loading wire:target="editar_estado">
                            Procesando <span class="spinner-border spinner-border-sm align-middle ms-2">
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            //Select2 de Filtro
            //Filtro de proceso_filtro select2
            $(document).ready(function() {
                $('#proceso_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#proceso_filtro').on('change', function() {
                    @this.set('proceso_filtro', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#proceso_filtro').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#proceso_filtro').on('change', function() {
                        @this.set('proceso_filtro', this.value);
                    });
                });
            });
            //Filtro de modalidad_filtro select2
            $(document).ready(function() {
                $('#modalidad_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#modalidad_filtro').on('change', function() {
                    @this.set('modalidad_filtro', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#modalidad_filtro').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#modalidad_filtro').on('change', function() {
                        @this.set('modalidad_filtro', this.value);
                    });
                });
            });
            //Filtro de programa_filtro select2
            $(document).ready(function() {
                $('#programa_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#programa_filtro').on('change', function() {
                    @this.set('programa_filtro', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#programa_filtro').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#programa_filtro').on('change', function() {
                        @this.set('programa_filtro', this.value);
                    });
                });
            });
            //Filtro de seguimiento_filtro de select2
            $(document).ready(function() {
                $('#seguimiento_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#seguimiento_filtro').on('change', function() {
                    @this.set('seguimiento_filtro', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#seguimiento_filtro').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#seguimiento_filtro').on('change', function() {
                        @this.set('seguimiento_filtro', this.value);
                    });
                });
            });
            //Filtro de mes_filtro de select2
            $(document).ready(function() {
                $('#mes_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#mes_filtro').on('change', function() {
                    @this.set('mes_filtro', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#mes_filtro').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#mes_filtro').on('change', function() {
                        @this.set('mes_filtro', this.value);
                    });
                });
            });
            //Select2 de Modal Inscripcion
            //Filtro de modalidad de select2
            $(document).ready(function() {
                $('#modalidad').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#modalidad').on('change', function() {
                    @this.set('modalidad', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#modalidad').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#modalidad').on('change', function() {
                        @this.set('modalidad', this.value);
                    });
                });
            });
            //Filtro de programa de select2
            $(document).ready(function() {
                $('#programa').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#programa').on('change', function() {
                    @this.set('programa', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#programa').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#programa').on('change', function() {
                        @this.set('programa', this.value);
                    });
                });
            });
        </script>
    @endpush
</div>
