<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Gestión de Cursos
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Gestión de Cursos
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-2 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold fs-5">
                                A continuación se muestra la lista de cursos que pertenecen a su Unidad.
                            </span>
                        </div>
                    </div>
                    {{-- card la tabla --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            {{-- header de la tabla --}}
                            <div class="d-flex flex-column flex-md-row align-items-center mb-5 w-100">
                                <div class="col-md-4 pe-md-3 mb-2 mb-md-0">
                                    <button type="button" class="btn btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-center fw-bold w-100px w-md-125px"
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
                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-350px w-lg-500px"
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
                                                    Plan de Estudios:
                                                </label>
                                                <div>
                                                    <select class="form-select" wire:model="filtro_plan"
                                                        id="filtro_plan" data-control="select2"
                                                        data-placeholder="Seleccione su plan">
                                                        <option value=""></option>
                                                        @foreach ($planes as $item)
                                                            <option value="{{ $item->id_plan }}">
                                                                PLAN {{ $item->plan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-5">
                                                <label class="form-label fw-semibold">
                                                    Programa Académico:
                                                </label>
                                                <div>
                                                    <select class="form-select" wire:model="filtro_programa"
                                                        id="filtro_programa" data-control="select2"
                                                        data-placeholder="Seleccione su programa">
                                                        <option value=""></option>
                                                        @foreach ($programas as $item)
                                                            <option value="{{ $item->id_programa }}">
                                                                @if ($item->mencion)
                                                                    MENCIÓN EN {{ $item->mencion }} - MODALIDAD {{ $item->modalidad->modalidad }}
                                                                @else
                                                                    {{ $item->programa }} EN {{ $item->subprograma }} - MODALIDAD {{ $item->modalidad->modalidad }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-10">
                                                <label class="form-label fw-semibold">
                                                    Ciclo Académico:
                                                </label>
                                                <div>
                                                    <select class="form-select" wire:model="filtro_ciclo"
                                                        id="filtro_ciclo" data-control="select2"
                                                        data-placeholder="Seleccione su ciclo">
                                                        <option value=""></option>
                                                        @foreach ($ciclos as $item)
                                                            <option value="{{ $item->id_ciclo }}">
                                                                CICLO {{ $item->ciclo }}
                                                            </option>
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
                                    </div>
                                </div>
                                <div class="col-md-4 px-md-3 mb-2 mb-md-0"></div>
                                <div class="col-md-4 ps-md-3">
                                    <input type="search" wire:model="search" class="form-control w-100"
                                        placeholder="Buscar..." />
                                </div>
                            </div>
                            @if ($programa_data)
                                @php
                                    $programa_data_show = App\Models\Programa::find($programa_data);
                                    $ciclo_data_show = $ciclo_data ? App\Models\Ciclo::find($ciclo_data) : null;
                                @endphp
                                <div class="mb-3">
                                    <span class="fs-4 text-gray-800">
                                        @if ($programa_data_show->mencion)
                                            PROGRAMA SELECCIONADO: <strong>MENCIÓN EN {{ $programa_data_show->mencion }} - MODALIDAD {{ $programa_data_show->modalidad->modalidad }} {{ $ciclo_data_show ? "- CICLO " . $ciclo_data_show->ciclo : "" }}</strong>
                                        @else
                                            PROGRAMA SELECCIONADO: <strong>{{ $programa_data_show->programa }} EN {{ $programa_data_show->subprograma }} - MODALIDAD {{ $programa_data_show->modalidad->modalidad }} {{ $ciclo_data_show ? "- CICLO " . $ciclo_data_show->ciclo : "" }}</strong>
                                        @endif
                                    </span>
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Nombre del Curso</th>
                                            <th>Créditos</th>
                                            <th>Ciclo</th>
                                            <th>Plan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-700">
                                        @forelse ($cursos as $item)
                                            <tr>
                                                <td class="fw-bold fs-6">
                                                    {{ $item->id_curso_programa_plan }}
                                                </td>
                                                <td class="fw-bold fs-6">
                                                    {{ $item->curso_codigo }}
                                                </td>
                                                <td class="fs-6 text-uppercase">
                                                    {{ $item->curso_nombre }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->curso_credito }}
                                                </td>
                                                <td class="fs-6">
                                                    <span class="badge badge-light-primary fs-6 px-3 py-2">
                                                        CICLO {{ $item->ciclo }}
                                                    </span>
                                                </td>
                                                <td class="fs-6">
                                                    <span class="badge badge-light-success fs-6 px-3 py-2">
                                                        PLAN {{ $item->plan }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
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
                                                            <a href="#modal_curso_detalle"
                                                                wire:click="cargar_datos({{ $item->id_curso_programa_plan }}, 'show')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_curso_detalle">
                                                                Detalle
                                                            </a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a href="#modal_docente"
                                                                wire:click="cargar_datos({{ $item->id_curso_programa_plan }}, 'select')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_asignacion_docente">
                                                                Asignar Docente
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
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
                            @if ($cursos->hasPages())
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $cursos->firstItem() }} - {{ $cursos->lastItem() }} de
                                        {{ $cursos->total() }} registros
                                    </div>
                                    <div>
                                        {{ $cursos->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $cursos->firstItem() }} - {{ $cursos->lastItem() }} de
                                        {{ $cursos->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_asignacion_docente">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $title_modal }}
                    </h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close"
                        wire:click="limpiar_modal">
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
                                    INFORMACIÓN DEL CURSO
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Código de Curso
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $curso ? $curso->curso_codigo : '' }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Nombre de Curso
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $curso ? $curso->curso_nombre : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="docente" class="required form-label fw-semibold text-gray-600 fs-5">
                                Buscar Docente
                            </label>
                            <select class="form-select @error('docente') is-invalid @enderror"
                                wire:model="docente" id="docente" data-control="select2"
                                data-placeholder="Buscar docente por si DNI o Apellidos y Nombres" data-allow-clear="true"
                                data-dropdown-parent="#modal_asignacion_docente">
                                <option></option>
                                @foreach ($docentes_model as $item)
                                    <option value="{{ $item->id_docente }}">
                                        DNI: {{ $item->trabajador_numero_documento }} - {{ $item->trabajador_apellido }}, {{ $item->trabajador_nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('docente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="proceso" class="required form-label fw-semibold text-gray-600 fs-5">
                                Proceso Académico
                            </label>
                            <select class="form-select @error('proceso') is-invalid @enderror"
                                wire:model="proceso" id="proceso" data-control="select2"
                                data-placeholder="Buscar proceso academico" data-allow-clear="true"
                                data-dropdown-parent="#modal_asignacion_docente">
                                <option></option>
                                @foreach ($procesos as $item)
                                    <option value="{{ $item->id_admision }}">
                                        {{ formatearAdmisionVisual($item->admision) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proceso')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="grupo" class="required form-label fw-semibold text-gray-600 fs-5">
                                Seleccionar Grupo
                            </label>
                            <select class="form-select @error('grupo') is-invalid @enderror"
                                wire:model="grupo" id="grupo" data-control="select2"
                                data-placeholder="Buscar grupo" data-allow-clear="true"
                                data-dropdown-parent="#modal_asignacion_docente">
                                <option></option>
                                @if ($grupos)
                                @foreach ($grupos as $item)
                                    <option value="{{ $item->id_programa_proceso_grupo }}">
                                        GRUPO: {{ $item->grupo_detalle }}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                            @error('grupo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <div class="text-end">
                                <button type="button" wire:click="cancelar_modal" class="btn fw-bold fs-5 btn-light">
                                    Cancelar
                                </button>
                                <button type="button" wire:click="asignar_docente" class="btn fw-bold fs-5 btn-primary">
                                    Asignar Docente
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="separator separator-dotted separator-content border-dark my-3">x</div>
                        </div>
                        <span class="col-12 fw-bold text-gray-700 fs-3">
                            DOCENTES ASIGNADOS
                        </span>
                        {{-- alerta --}}
                        <div  class="col-12">
                            <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-0">
                                <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 d-flex align-items-center">
                                    <i class="las la-exclamation-circle fs-1 text-primary"></i>
                                </span>
                                <div class="d-flex flex-column gap-2">
                                    <span class="fw-bold fs-5">
                                        Para desactivar o activar un docente, haga click en el botón <span class="badge badge-danger px-3 py-2 fs-6">Inactivo</span> o <span class="badge badge-primary px-3 py-2 fs-6">Activo</span> respectivamente.
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                <thead class="bg-light-warning">
                                    <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                        <th>#</th>
                                        <th>Documento</th>
                                        <th>Apellido y Nombres</th>
                                        <th>Grado Académico</th>
                                        <th>Proceso Académico</th>
                                        <th>Grupo</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @if ($docentes)
                                        @forelse ($docentes as $item)
                                            <tr>
                                                <td class="fw-bold fs-6">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="fw-bold fs-6">
                                                    {{ $item->docente->trabajador->trabajador_numero_documento }}
                                                </td>
                                                <td class="fs-6 text-uppercase">
                                                    {{ $item->docente->trabajador->trabajador_apellido }}, {{ $item->docente->trabajador->trabajador_nombre }}
                                                </td>
                                                <td class="fs-6">
                                                    <span class="badge badge-light-info fs-6 px-3 py-2">
                                                        {{ $item->docente->trabajador->grado_academico->grado_academico }}
                                                    </span>
                                                </td>
                                                <td class="fs-6">
                                                    <span class="badge badge-light-secondary text-gray-700 fs-6 px-3 py-2">
                                                        {{ formatearAdmisionVisual($item->admision->admision) }}
                                                    </span>
                                                </td>
                                                <td class="fs-6">
                                                    <span class="badge badge-light-success fs-6 px-3 py-2">
                                                        GRUPO {{ $item->programa_proceso_grupo->grupo_detalle }}
                                                    </span>
                                                </td>
                                                <td class="fs-6">
                                                    @if ($item->docente_curso_estado == 1)
                                                        <span class="badge badge-primary fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado({{ $item->id_docente_curso }})"
                                                            style="cursor: pointer;">
                                                            Activo
                                                        </span>
                                                    @elseif ($item->docente_curso_estado == 0)
                                                        <span class="badge badge-danger fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado({{ $item->id_docente_curso }})"
                                                            style="cursor: pointer;">
                                                            Inactivo
                                                        </span>
                                                    @else
                                                        <span class="badge badge-light-warning fs-6 px-3 py-2">
                                                            Curso Finalizado
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="fs-6">
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
                                                    <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-175px py-4"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a style="cursor: pointer;"
                                                                wire:click="alerta_eliminar_docente_asignado({{ $item->id_docente_curso }})"
                                                                class="menu-link px-3 fs-6">
                                                                Eliminar Docente
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
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_curso_detalle">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $title_modal }}
                    </h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close"
                        wire:model="limpiar_modal">
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
                    <form autocomplete="off" class="row py-3">
                        <div class="col-md-12 px-md-7">
                            <div class="row mb-3">
                                <span class="col-12 fw-bold text-gray-800 fs-3">
                                    INFORMACIÓN DEL CURSO
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Código de Curso
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $curso ? $curso->curso_codigo : '' }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Nombre de Curso
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $curso ? $curso->curso_nombre : '' }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Créditos
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $curso ? $curso->curso_credito : '' }} créditos
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Horas
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $curso ? $curso->curso_horas : '' }} hrs.
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Ciclo Académico
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    CICLO {{ $curso ? $curso->ciclo->ciclo : '' }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Plan de Estudios
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    PLAN {{ $curso_programa_plan ? $curso_programa_plan->programa_plan->plan->plan : '' }}
                                </span>
                            </div>
                            <div class="row mt-10">
                                <span class="col-12 fw-bold text-gray-700 fs-3 mb-3">
                                    DOCENTES ASIGNADOS
                                </span>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                        <thead class="bg-light-warning">
                                            <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                                <th>Documento</th>
                                                <th>Apellido y Nombres</th>
                                                <th>Grado Académico</th>
                                                <th>Proceso Académico</th>
                                                <th>Grupo</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-700">
                                            @if ($docentes)
                                                @forelse ($docentes as $item)
                                                    <tr>
                                                        <td class="fw-bold fs-6">
                                                            {{ $item->docente->trabajador->trabajador_numero_documento }}
                                                        </td>
                                                        <td class="fs-6 text-uppercase">
                                                            {{ $item->docente->trabajador->trabajador_apellido }}, {{ $item->docente->trabajador->trabajador_nombre }}
                                                        </td>
                                                        <td class="fs-6">
                                                            <span class="badge badge-light-info fs-6 px-3 py-2">
                                                                {{ $item->docente->trabajador->grado_academico->grado_academico }}
                                                            </span>
                                                        </td>
                                                        <td class="fs-6">
                                                            <span class="badge badge-light-secondary text-gray-700 fs-6 px-3 py-2">
                                                                {{ formatearAdmisionVisual($item->admision->admision) }}
                                                            </span>
                                                        </td>
                                                        <td class="fs-6">
                                                            <span class="badge badge-light-success fs-6 px-3 py-2">
                                                                GRUPO {{ $item->programa_proceso_grupo->grupo_detalle }}
                                                            </span>
                                                        </td>
                                                        <td class="fs-6">
                                                            @if ($item->docente_curso_estado == 1)
                                                                <span class="badge badge-primary fs-6 px-3 py-2">
                                                                    Activo
                                                                </span>
                                                            @elseif ($item->docente_curso_estado == 0)
                                                                <span class="badge badge-danger fs-6 px-3 py-2">
                                                                    Inactivo
                                                                </span>
                                                            @else
                                                                <span class="badge badge-light-warning fs-6 px-3 py-2">
                                                                    Curso Finalizado
                                                                </span>
                                                            @endif
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
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // grupo select2
        $(document).ready(function() {
            $('#grupo').select2({
                placeholder: 'Seleccione su grupo',
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
            $('#grupo').on('change', function() {
                @this.set('grupo', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#grupo').select2({
                    placeholder: 'Seleccione su grupo',
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
                $('#grupo').on('change', function() {
                    @this.set('grupo', this.value);
                });
            });
        });
        // docente select2
        $(document).ready(function() {
            $('#docente').select2({
                placeholder: 'Buscar docente por si DNI o Apellidos y Nombres',
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
            $('#docente').on('change', function() {
                @this.set('docente', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#docente').select2({
                    placeholder: 'Buscar docente por si DNI o Apellidos y Nombres',
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
                $('#docente').on('change', function() {
                    @this.set('docente', this.value);
                });
            });
        });
        // proceso select2
        $(document).ready(function() {
            $('#proceso').select2({
                placeholder: 'Seleccione su proceso academico',
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
                    placeholder: 'Seleccione su proceso academico',
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
        // filtro_plan select2
        $(document).ready(function() {
            $('#filtro_plan').select2({
                placeholder: 'Seleccione su plan',
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
            $('#filtro_plan').on('change', function() {
                @this.set('filtro_plan', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_plan').select2({
                    placeholder: 'Seleccione su plan',
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
                $('#filtro_plan').on('change', function() {
                    @this.set('filtro_plan', this.value);
                });
            });
        });
        // filtro_programa select2
        $(document).ready(function() {
            $('#filtro_programa').select2({
                placeholder: 'Seleccione su programa',
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
            $('#filtro_programa').on('change', function() {
                @this.set('filtro_programa', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_programa').select2({
                    placeholder: 'Seleccione su programa',
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
                $('#filtro_programa').on('change', function() {
                    @this.set('filtro_programa', this.value);
                });
            });
        });
        // filtro_ciclo select2
        $(document).ready(function() {
            $('#filtro_ciclo').select2({
                placeholder: 'Seleccione su ciclo',
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
            $('#filtro_ciclo').on('change', function() {
                @this.set('filtro_ciclo', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_ciclo').select2({
                    placeholder: 'Seleccione su ciclo',
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
                $('#filtro_ciclo').on('change', function() {
                    @this.set('filtro_ciclo', this.value);
                });
            });
        });
    </script>
@endpush
