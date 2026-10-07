<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack flex-wrap gap-3">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Inscripciones
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
                        <li class="breadcrumb-item text-muted">Gestión de Admisión</li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Inscripciones</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <button type="button" wire:click="excel" class="btn btn-sm btn-light-success hover-elevate-up"
                        wire:loading.attr="disabled" wire:target="excel">
                        <span wire:loading.remove wire:target="excel">
                            <i class="bi bi-file-earmark-excel fs-4 me-1"></i>
                            Exportar a Excel
                        </span>
                        <span wire:loading wire:target="excel">
                            <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            Exportando...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">

                {{-- Resumen del proceso seleccionado --}}
                @if ($resumen)
                    @php
                        $tarjetasResumen = [
                            ['valor' => $resumen->inscritos, 'texto' => 'Inscritos', 'color' => 'text-gray-800', 'icono' => 'bi-people'],
                            ['valor' => $resumen->verificados, 'texto' => 'Verificados', 'color' => 'text-success', 'icono' => 'bi-check-circle'],
                            ['valor' => $resumen->pendientes, 'texto' => 'Pendientes', 'color' => 'text-warning', 'icono' => 'bi-hourglass-split'],
                            ['valor' => $resumen->observados, 'texto' => 'Observados', 'color' => 'text-danger', 'icono' => 'bi-exclamation-circle'],
                            ['valor' => $resumen->expedientes_observados, 'texto' => 'Expedientes observados', 'color' => 'text-danger', 'icono' => 'bi-file-earmark-x'],
                            ['valor' => $resumen->reservados, 'texto' => 'Reservados / trasladados', 'color' => 'text-gray-600', 'icono' => 'bi-bookmark'],
                        ];
                    @endphp
                    <div class="row g-3 g-lg-5 mb-5">
                        @foreach ($tarjetasResumen as $card)
                            <div class="col-6 col-md-4 col-xl-2">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body d-flex align-items-center gap-3 py-4 px-5">
                                        <i class="bi {{ $card['icono'] }} fs-2x {{ $card['color'] }}"></i>
                                        <div class="d-flex flex-column">
                                            <span class="fs-2 fw-bold {{ $card['color'] }} lh-1">{{ (int) $card['valor'] }}</span>
                                            <span class="fs-7 fw-semibold text-gray-500 mt-1">{{ $card['texto'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="card shadow-sm">
                    {{-- Barra de busqueda y filtros --}}
                    <div class="card-header border-0 pt-6 flex-wrap gap-3">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="bi bi-search fs-5 text-gray-500 position-absolute ms-4"></i>
                                <input type="search" wire:model.debounce.500ms="search"
                                    class="form-control form-control-solid w-250px w-md-400px ps-12"
                                    placeholder="Buscar por nombre, DNI, código o programa">
                            </div>
                        </div>
                        <div class="card-toolbar flex-wrap gap-3">
                            <select class="form-select form-select-solid w-200px" wire:model="estado_expediente_filtro">
                                <option value="all">Todos los expedientes</option>
                                <option value="0">Expedientes pendientes</option>
                                <option value="1">Expedientes verificados</option>
                                <option value="2">Expedientes observados</option>
                            </select>

                            <button type="button" class="btn btn-light-primary fw-bold" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                                <i class="bi bi-funnel fs-4 me-1"></i>
                                Filtros
                                @if (count($filtrosAplicados) > 0)
                                    <span class="badge badge-circle badge-primary ms-2">{{ count($filtrosAplicados) }}</span>
                                @endif
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-550px" data-kt-menu="true"
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
                                                    <option value="{{ $item->id_admision }}">
                                                        {{ formatearAdmisionVisual($item->admision) }}{{ $item->admision_estado == 1 ? ' (Activo)' : '' }}
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
                                                @foreach ($programasFiltro as $item)
                                                    <option value="{{ $item->id_programa }}">
                                                        {{ $item->programa }} EN {{ $item->subprograma }}
                                                        @if ($item->mencion != '')
                                                            CON MENCION EN {{ $item->mencion }}
                                                        @endif
                                                        - {{ $item->modalidad }}
                                                    </option>
                                                @endforeach
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
                                        <label class="form-label fw-semibold">Mes de inscripción:</label>
                                        <div>
                                            <select class="form-select" wire:model="mes_filtro" id="mes_filtro"
                                                data-control="select2"
                                                data-placeholder="{{ $proceso_filtro ? 'Seleccione el Mes' : 'Primero seleccione el proceso' }}">
                                                <option></option>
                                                @foreach ($mesesFiltro as $item)
                                                    <option value="{{ $item->mes }}">
                                                        {{ $meses[$item->mes] }} {{ $item->anio }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-5 col-md-6">
                                        <label class="form-label fw-semibold">Estado de la inscripción:</label>
                                        <div>
                                            <select class="form-select" wire:model="estado_filtro">
                                                <option value="">
                                                    Todos los estados
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

                            <div class="d-flex align-items-center gap-2 text-gray-600 fs-7">
                                Mostrar
                                <select class="form-select form-select-solid form-select-sm w-80px"
                                    wire:model="cant_paginas">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="150">150</option>
                                    <option value="200">200</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Filtros aplicados --}}
                    @if (count($filtrosAplicados) > 0)
                        <div class="card-body py-0 d-flex flex-wrap align-items-center gap-2">
                            <span class="text-gray-600 fs-7 fw-semibold me-1">Filtros aplicados:</span>
                            @foreach ($filtrosAplicados as $filtro => $etiqueta)
                                <span class="badge badge-light-primary fs-7 fw-semibold py-2 px-3 d-inline-flex align-items-center">
                                    {{ $etiqueta }}
                                    <i class="bi bi-x-lg text-primary fs-8 ms-2 cursor-pointer"
                                        wire:click="quitar_filtro('{{ $filtro }}')" title="Quitar filtro"></i>
                                </span>
                            @endforeach
                            <button type="button" class="btn btn-sm btn-link text-danger fs-7 p-0 ms-2"
                                wire:click="resetear_filtro">
                                Restablecer filtros
                            </button>
                        </div>
                    @endif

                    {{-- Tabla de inscripciones --}}
                    <div class="card-body pt-5">
                        <div class="table-responsive position-relative">
                            <div wire:loading.flex
                                wire:target="search, estado_expediente_filtro, cant_paginas, filtrar, resetear_filtro, quitar_filtro, gotoPage, nextPage, previousPage"
                                class="position-absolute top-0 start-0 w-100 h-100 bg-body bg-opacity-75 justify-content-center align-items-start pt-20"
                                style="z-index: 3;">
                                <div class="d-flex align-items-center gap-3 text-gray-700 fw-semibold">
                                    <span class="spinner-border spinner-border-sm text-primary"></span>
                                    Cargando inscripciones...
                                </div>
                            </div>
                            <table class="table table-row-dashed table-row-gray-300 align-middle gy-4 mb-0">
                                <thead>
                                    <tr class="fw-bold fs-7 text-gray-600 text-uppercase bg-light">
                                        <th class="ps-4 min-w-125px rounded-start">Código</th>
                                        <th class="min-w-225px">Postulante</th>
                                        <th class="min-w-300px">Programa</th>
                                        <th class="text-center min-w-100px">Fecha</th>
                                        <th class="text-center min-w-110px">Inscripción</th>
                                        <th class="text-center min-w-110px">Expedientes</th>
                                        <th class="text-end pe-4 min-w-100px rounded-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inscripcionModel as $item)
                                        @php
                                            $trasladada = in_array($item->id_inscripcion, $inscripcionesTrasladadas);
                                        @endphp
                                        <tr wire:key="{{ $item->id_inscripcion }}" class="{{ $item->retiro_inscripcion == 1 ? 'bg-light-warning' : '' }}">
                                            <td class="ps-4">
                                                <span class="fw-bold text-gray-800">{{ $item->inscripcion_codigo }}</span>
                                                <div class="text-muted fs-8">ID {{ $item->id_inscripcion }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 fw-semibold mb-1">
                                                        {{ $item->apellido_paterno }} {{ $item->apellido_materno }},
                                                        {{ $item->nombre }}
                                                    </span>
                                                    <span class="text-gray-600 fs-7">
                                                        <i class="bi bi-person-vcard me-1"></i>{{ $item->numero_documento }}
                                                        <span class="mx-1">·</span>
                                                        <i class="bi bi-telephone me-1"></i>{{ $item->celular }}
                                                    </span>
                                                    @if ($item->correo)
                                                        <span class="text-gray-500 fs-7 text-break">{{ $item->correo }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-gray-800 fs-7 fw-semibold mb-2">
                                                    {{ $item->programa }} EN {{ $item->subprograma }}
                                                    @if ($item->mencion != '')
                                                        CON MENCION EN {{ $item->mencion }}
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <span class="badge badge-light-primary">{{ $item->modalidad }}</span>
                                                    @if (!$procesoFiltro)
                                                        <span class="badge badge-light">{{ formatearAdmisionVisual($item->admision) }}</span>
                                                    @endif
                                                    @if ($item->es_traslado_externo == 1)
                                                        <span class="badge badge-light-warning text-dark">Traslado Externo</span>
                                                    @endif
                                                    @if ($item->id_inscripcion_origen)
                                                        <span class="badge badge-light-info text-dark">Traslado de Proceso</span>
                                                    @endif
                                                    @if ($trasladada)
                                                        <span class="badge badge-light-dark text-dark">Trasladada al Proceso Actual</span>
                                                    @elseif ($item->retiro_inscripcion == 1)
                                                        <span class="badge badge-light-dark text-dark">Reservada</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center text-gray-700 fs-7">
                                                {{ date('d/m/Y', strtotime($item->inscripcion_fecha)) }}
                                            </td>
                                            <td class="text-center">
                                                @if ($item->inscripcion_estado == 1)
                                                    <span class="badge badge-light-success fs-7 px-3 py-2">
                                                        Verificado
                                                    </span>
                                                @elseif($item->inscripcion_estado == 2)
                                                    <span class="badge badge-light-danger fs-7 px-3 py-2"
                                                        title="{{ $item->inscripcion_observacion }}">
                                                        Observado
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-warning fs-7 px-3 py-2">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($item->verificar_expedientes == 1)
                                                    <span class="badge badge-success badge-outline fs-7 px-3 py-2">
                                                        Verificado
                                                    </span>
                                                @elseif($item->verificar_expedientes == 2)
                                                    <span class="badge badge-danger badge-outline fs-7 px-3 py-2">
                                                        Observado
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning badge-outline fs-7 px-3 py-2">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                {{-- strategy fixed: el menu se posiciona sobre la ventana y no queda recortado por el scroll de la tabla --}}
                                                <a class="btn btn-light btn-active-light-primary btn-sm text-nowrap d-inline-flex align-items-center"
                                                    data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}'>
                                                    Acciones
                                                    <i class="bi bi-chevron-down fs-8 ms-2"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-250px py-4"
                                                    data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#modal-expediente"
                                                            wire:click="cargar_expedientes({{ $item->id_inscripcion }}, 3)"
                                                            class="menu-link px-3" data-bs-toggle="modal"
                                                            data-bs-target="#modal-expediente">
                                                            <i class="bi bi-folder2-open fs-5 me-3"></i>
                                                            Ver Expedientes
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#modal-estado-inscripcion"
                                                            wire:click="cargar_inscripcion({{ $item->id_inscripcion }})"
                                                            class="menu-link px-3" data-bs-toggle="modal"
                                                            data-bs-target="#modal-estado-inscripcion">
                                                            <i class="bi bi-ui-checks fs-5 me-3"></i>
                                                            Editar Estado de Inscripción
                                                        </a>
                                                    </div>
                                                    @if ($admisionActiva && $item->id_admision == $admisionActiva->id_admision)
                                                        <div class="menu-item px-3">
                                                            <a href="#ModalInscripcionEditar"
                                                                wire:click="cargarInscripcion({{ $item->id_inscripcion }}, 2)"
                                                                class="menu-link px-3" data-bs-toggle="modal"
                                                                data-bs-target="#ModalInscripcionEditar">
                                                                <i class="bi bi-pencil-square fs-5 me-3"></i>
                                                                Editar Programa
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="menu-item px-3">
                                                        <a wire:click="actualizar_ficha_inscripcion({{ $item->id_inscripcion }})"
                                                            class="menu-link px-3 cursor-pointer">
                                                            <i class="bi bi-file-earmark-arrow-up fs-5 me-3"></i>
                                                            Actualizar Ficha de Inscripción
                                                        </a>
                                                    </div>
                                                    @if ($admisionActiva &&
                                                            $item->id_admision != $admisionActiva->id_admision &&
                                                            $item->retiro_inscripcion != 1 &&
                                                            $item->inscripcion_estado != 2 &&
                                                            !in_array($item->id_programa_proceso, $programasConAdmitidos))
                                                        <div class="menu-item px-3">
                                                            <a href="#modal-traslado-inscripcion"
                                                                wire:click="cargar_traslado({{ $item->id_inscripcion }})"
                                                                class="menu-link px-3" data-bs-toggle="modal"
                                                                data-bs-target="#modal-traslado-inscripcion">
                                                                <i class="bi bi-arrow-left-right fs-5 me-3"></i>
                                                                Trasladar al Proceso Actual
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @if ($item->retiro_inscripcion != 1)
                                                        <div class="menu-item px-3">
                                                            <a wire:click="confirmar_reserva({{ $item->id_inscripcion }})"
                                                                class="menu-link px-3 cursor-pointer">
                                                                <i class="bi bi-bookmark fs-5 me-3"></i>
                                                                Reservar Inscripción
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="separator my-2"></div>
                                                    <div class="menu-item px-3">
                                                        <a wire:click="eliminar_inscripcion({{ $item->id_inscripcion }})"
                                                            class="menu-link px-3 cursor-pointer text-danger">
                                                            <i class="bi bi-trash fs-5 me-3 text-danger"></i>
                                                            Eliminar Inscripción
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-15">
                                                <i class="bi bi-inbox fs-3x text-gray-400"></i>
                                                <div class="text-gray-600 fw-semibold mt-3">
                                                    @if ($search != '')
                                                        No se encontraron resultados para la búsqueda "{{ $search }}"
                                                    @else
                                                        No hay inscripciones con los filtros seleccionados
                                                    @endif
                                                </div>
                                                @if (count($filtrosAplicados) > 0)
                                                    <button type="button" class="btn btn-sm btn-light-primary mt-4"
                                                        wire:click="resetear_filtro">
                                                        Restablecer filtros
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="text-gray-700 fs-7">
                            @if ($inscripcionModel->total() > 0)
                                Mostrando {{ $inscripcionModel->firstItem() }} - {{ $inscripcionModel->lastItem() }}
                                de {{ $inscripcionModel->total() }} inscripciones
                            @else
                                0 inscripciones
                            @endif
                        </div>
                        @if ($inscripcionModel->hasPages())
                            <div>
                                {{ $inscripcionModel->links() }}
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
                    <div>
                        <h3 class="modal-title">
                            Expedientes de Inscripción
                        </h3>
                        <div class="text-gray-700 fs-7 fw-semibold mt-1" wire:loading.remove wire:target="cargar_expedientes">
                            {{ $expediente_postulante }}
                        </div>
                        <div class="text-muted fs-8" wire:loading.remove wire:target="cargar_expedientes">
                            {{ $expediente_programa }}
                        </div>
                    </div>
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="bi bi-x-lg fs-3"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div wire:loading.flex wire:target="cargar_expedientes" class="justify-content-center py-10">
                        <span class="spinner-border text-primary"></span>
                    </div>
                    @php
                        $expedientesLista = collect($expedientes);
                        $expedientesVerificados = $expedientesLista->where('expediente_inscripcion_verificacion', 1)->count();
                        $expedientesPendientes = $expedientesLista->where('expediente_inscripcion_verificacion', 0)->count();
                        $expedientesTotal = $expedientesLista->count();
                    @endphp
                    <div wire:loading.remove wire:target="cargar_expedientes">
                        @if ($expedientesTotal > 0)
                            <div class="d-flex align-items-center gap-3 mb-5">
                                <span class="fw-semibold text-gray-700 fs-7 text-nowrap">
                                    {{ $expedientesVerificados }} de {{ $expedientesTotal }} expedientes verificados
                                </span>
                                <div class="progress h-6px w-100 bg-light-success">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: {{ $expedientesTotal ? round($expedientesVerificados * 100 / $expedientesTotal) : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gy-4 mb-0">
                                <thead>
                                    <tr class="fw-bold fs-7 text-gray-600 text-uppercase bg-light">
                                        <th class="ps-4 rounded-start">#</th>
                                        <th class="min-w-250px">Expediente</th>
                                        <th class="text-center">Archivo</th>
                                        <th class="text-center min-w-150px">Fecha</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-end pe-4 min-w-225px rounded-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expedientes as $item)
                                        <tr wire:key="expediente-{{ $item->id_expediente_inscripcion }}">
                                            <td class="ps-4 fw-bold">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td class="text-gray-800 fw-semibold">
                                                {{ $item->expediente_admision->expediente->expediente }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ asset($item->expediente_inscripcion_url) }}"
                                                    target="_blank" class="btn btn-sm btn-light-primary text-nowrap">
                                                    <i class="bi bi-eye fs-5"></i>
                                                    Ver archivo
                                                </a>
                                            </td>
                                            <td class="text-center text-gray-700 fs-7">
                                                {{ convertirFechaHora($item->expediente_inscripcion_fecha) }}
                                            </td>
                                            <td class="text-center">
                                                @if ($item->expediente_inscripcion_verificacion == 1)
                                                    <span class="badge badge-light-success fs-7 px-3 py-2">
                                                        Verificado
                                                    </span>
                                                @elseif($item->expediente_inscripcion_verificacion == 2)
                                                    <span class="badge badge-light-danger fs-7 px-3 py-2">
                                                        Rechazado
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-warning fs-7 px-3 py-2">
                                                        Pendiente
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4 text-nowrap">
                                                @if ($item->expediente_inscripcion_verificacion != 1)
                                                    <button type="button" class="btn btn-sm btn-light-success"
                                                        wire:click="verificar_expediente({{ $item->id_expediente_inscripcion }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="verificar_expediente, rechazar_expediente, verificar_expedientes_pendientes">
                                                        <span wire:loading.remove
                                                            wire:target="verificar_expediente({{ $item->id_expediente_inscripcion }})">
                                                            <i class="bi bi-check-lg fs-5"></i>
                                                            Verificar
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="verificar_expediente({{ $item->id_expediente_inscripcion }})">
                                                            <span class="spinner-border spinner-border-sm align-middle me-1"></span>
                                                            Verificando...
                                                        </span>
                                                    </button>
                                                @endif
                                                @if ($item->expediente_inscripcion_verificacion != 2)
                                                    <button type="button" class="btn btn-sm btn-light-danger ms-1"
                                                        wire:click="rechazar_expediente({{ $item->id_expediente_inscripcion }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="verificar_expediente, rechazar_expediente, verificar_expedientes_pendientes">
                                                        <span wire:loading.remove
                                                            wire:target="rechazar_expediente({{ $item->id_expediente_inscripcion }})">
                                                            <i class="bi bi-x-lg fs-6"></i>
                                                            Rechazar
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="rechazar_expediente({{ $item->id_expediente_inscripcion }})">
                                                            <span class="spinner-border spinner-border-sm align-middle me-1"></span>
                                                            Rechazando...
                                                        </span>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-10">
                                                La inscripción no tiene expedientes registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="text-muted fs-7">
                        Al verificar todos los expedientes, la inscripción queda verificada y se notifica al postulante.
                    </span>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        @if ($expedientesPendientes > 0)
                            <button type="button" class="btn btn-success" wire:click="verificar_expedientes_pendientes"
                                wire:loading.attr="disabled"
                                wire:target="verificar_expediente, rechazar_expediente, verificar_expedientes_pendientes, cargar_expedientes">
                                <span wire:loading.remove wire:target="verificar_expedientes_pendientes">
                                    <i class="bi bi-check2-all fs-4"></i>
                                    Verificar pendientes ({{ $expedientesPendientes }})
                                </span>
                                <span wire:loading wire:target="verificar_expedientes_pendientes">
                                    <span class="spinner-border spinner-border-sm align-middle me-1"></span>
                                    Verificando...
                                </span>
                            </button>
                        @endif
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
                        <i class="bi bi-x-lg fs-3"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div wire:loading.flex wire:target="cargarInscripcion" class="justify-content-center py-10">
                        <span class="spinner-border text-primary"></span>
                    </div>
                    <form autocomplete="off" class="row g-5" wire:loading.remove wire:target="cargarInscripcion">
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
                                    <option value="{{ $item->id_modalidad }}">{{ $item->modalidad }}</option>
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
                        <div class="col-md-12">
                            <div class="text-muted fs-7">
                                Solo se muestran los programas del proceso de admisión activo. Al guardar se actualiza
                                la ficha de inscripción y se envía al correo del postulante.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar()">
                        Cerrar
                    </button>
                    <button type="button" wire:click="actualizarInscripcion" class="btn btn-primary"
                        style="width: 150px" wire:loading.attr="disabled"
                        wire:target="actualizarInscripcion, cargarInscripcion">
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
                        <i class="bi bi-x-lg fs-3"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div wire:loading.flex wire:target="cargar_inscripcion" class="justify-content-center py-10">
                        <span class="spinner-border text-primary"></span>
                    </div>
                    <form autocomplete="off" class="row g-5" wire:loading.remove wire:target="cargar_inscripcion">
                        <div class="col-md-12">
                            <label for="estado" class="form-label">
                                Estado
                            </label>
                            <select class="form-select @error('estado') is-invalid @enderror" wire:model="estado"
                                id="estado">
                                <option value="">Seleccione un estado...</option>
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
                            @error('estado')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($estado == 2)
                            <div class="col-md-12">
                                <label for="observacion_inscripcion" class="required form-label">
                                    Observación
                                </label>
                                <textarea class="form-control @error('observacion_inscripcion') is-invalid @enderror"
                                    wire:model.defer="observacion_inscripcion" id="observacion_inscripcion" rows="3" maxlength="100"
                                    placeholder="Indique el motivo de la observación"></textarea>
                                @error('observacion_inscripcion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        @if ($estado == 1 || $estado == 2)
                            <div class="col-md-12">
                                <div class="text-muted fs-7">
                                    <i class="bi bi-envelope me-1"></i>
                                    Se enviará un correo al postulante informando el nuevo estado.
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar()">
                        Cerrar
                    </button>
                    <button type="button" wire:click="editar_estado" class="btn btn-primary" style="width: 150px"
                        wire:loading.attr="disabled" wire:target="editar_estado, cargar_inscripcion">
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

    {{-- Modal Trasladar Inscripcion al Proceso Actual --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal-traslado-inscripcion">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Trasladar Inscripción al Proceso Actual
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" wire:click="limpiar_traslado()"
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg fs-3"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div wire:loading.flex wire:target="cargar_traslado" class="justify-content-center py-10">
                        <span class="spinner-border text-primary"></span>
                    </div>
                    <form autocomplete="off" class="row g-5" wire:loading.remove wire:target="cargar_traslado">
                        <div class="col-md-12">
                            <div class="d-flex flex-column gap-2 bg-light rounded p-5">
                                <span><span class="fw-bold">Postulante:</span> {{ $traslado_postulante }}</span>
                                <span><span class="fw-bold">Inscripción:</span> {{ $traslado_inscripcion }}</span>
                                <span><span class="fw-bold">Programa:</span> {{ $traslado_programa }}</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="programa_proceso_traslado" class="required form-label">
                                Programa destino en el proceso
                                {{ $admisionActiva ? formatearAdmisionVisual($admisionActiva->admision) : '' }}
                            </label>
                            <select class="form-select @error('programa_proceso_traslado') is-invalid @enderror"
                                wire:model="programa_proceso_traslado" id="programa_proceso_traslado">
                                <option value="">Seleccione el programa...</option>
                                @foreach ($programasTraslado as $item)
                                    <option value="{{ $item['id_programa_proceso'] }}">
                                        {{ $item['modalidad'] }} - {{ $item['programa'] }} EN {{ $item['subprograma'] }}
                                        @if ($item['mencion'] != '')
                                            CON MENCION EN {{ $item['mencion'] }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('programa_proceso_traslado')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="alert bg-light-primary border border-primary border-dashed mb-0">
                                <ul class="mb-0 ps-5">
                                    <li>Se crea una nueva inscripción en el proceso actual con el mismo pago, sin cobro adicional.</li>
                                    <li>Se copian los expedientes ya presentados y se conserva su verificación.</li>
                                    <li>Si el programa destino pide un expediente que no presentó (por ejemplo, el tema de tesis
                                        de doctorado), la inscripción quedará pendiente hasta que lo suba.</li>
                                    <li>La inscripción original queda reservada en su proceso.</li>
                                    <li>Se envía la nueva ficha de inscripción al correo del postulante.</li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="limpiar_traslado()">
                        Cerrar
                    </button>
                    <button type="button" wire:click="trasladar_inscripcion" class="btn btn-primary"
                        style="width: 150px" wire:loading.attr="disabled"
                        wire:target="trasladar_inscripcion, cargar_traslado">
                        <div wire:loading.remove wire:target="trasladar_inscripcion">
                            Trasladar
                        </div>
                        <div wire:loading wire:target="trasladar_inscripcion">
                            Procesando <span class="spinner-border spinner-border-sm align-middle ms-2">
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Select2 de los filtros y del modal de editar programa.
            // Livewire vuelve a pintar los select, por eso se reinicializan despues de cada actualizacion.
            // El evento change se registra una sola vez por select para no repetir peticiones.
            const selectsInscripcion = {
                '#proceso_filtro': 'proceso_filtro',
                '#modalidad_filtro': 'modalidad_filtro',
                '#programa_filtro': 'programa_filtro',
                '#seguimiento_filtro': 'seguimiento_filtro',
                '#mes_filtro': 'mes_filtro',
                '#modalidad': 'modalidad',
                '#programa': 'programa',
            };

            function initSelectsInscripcion() {
                Object.entries(selectsInscripcion).forEach(([selector, propiedad]) => {
                    const $select = $(selector);
                    if (!$select.length) {
                        return;
                    }
                    const parent = $select.data('dropdown-parent');
                    $select.select2({
                        placeholder: $select.data('placeholder') || 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        dropdownParent: parent ? $(parent) : $(document.body),
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $select.off('change.inscripcion').on('change.inscripcion', function() {
                        @this.set(propiedad, this.value);
                    });
                });
            }

            $(document).ready(function() {
                initSelectsInscripcion();
                Livewire.hook('message.processed', (message, component) => {
                    initSelectsInscripcion();
                });
            });
        </script>
    @endpush
</div>
