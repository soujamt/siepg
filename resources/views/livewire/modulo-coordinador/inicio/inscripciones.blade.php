<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    @if ($programa->mencion)
                        Inscripciones de la Mencion en {{ ucwords(strtolower($programa->mencion)) }} en Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de {{ ucwords(strtolower($admision->admision)) }}
                    @else
                    Inscripciones de la {{ ucwords(strtolower($programa->programa)) }} en {{ ucwords(strtolower($programa->subprograma)) }} en Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de {{ ucwords(strtolower($admision->admision)) }}
                    @endif
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Evaluaciones</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.programas', $programa->id_modalidad) }}" class="text-muted text-hover-primary">Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        @if ($programa->mencion)
                            Inscripciones de la Mencion en {{ ucwords(strtolower($programa->mencion)) }}
                        @else
                            Inscripciones de la {{ ucwords(strtolower($programa->programa)) }} en {{ ucwords(strtolower($programa->subprograma)) }}
                        @endif
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    <button type="button" class="btn btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor"/>
                                <path opacity="0.3" d="M13 14.4V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V14.4H13Z" fill="currentColor"/>
                                <path d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM13 14.4V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V14.4H8L11.3 17.7C11.7 18.1 12.3 18.1 12.7 17.7L16 14.4H13Z" fill="currentColor"/>
                            </svg>
                        </span>
                        Exportar
                    </button>
                    <div id="kt_datatable_example_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                        {{-- <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">
                            Exportar a PDF
                            </a>
                        </div> --}}
                        <div class="menu-item px-3">
                            <a wire:click="export_excel" style="cursor: pointer;" class="menu-link px-3 fs-5">
                                Exportar a Excel
                            </a>
                        </div>
                        {{-- <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">
                            Exportar a CSV
                            </a>
                        </div> --}}
                    </div>
                    <div id="kt_datatable_example_buttons" class="d-none"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold fs-5">
                                Los datos se pueden ordenar de forma "ascendente" o "descendente" haciendo click en el encabezado de la columna.
                            </span>
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="table-responsive">
                                <div class="d-flex flex-column flex-md-row align-items-center mb-5 w-100">
                                    <div class="col-md-4 pe-md-3"></div>
                                    <div class="col-md-4 px-md-3">
                                        {{-- <select class="form-select" wire:model="filtro_canal_pago" data-control="select2" id="filtro_canal_pago" data-placeholder="Seleccione el canal de pago">
                                            <option></option>
                                            <option value="all">Mostrar todos los pagos</option>
                                            @foreach ($canal_pagos as $item)
                                                <option value="{{ $item->id_canal_pago }}">Pago en {{ $item->canal_pago }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    <div class="col-md-4 ps-md-3">
                                        <input type="search" wire:model="search" class="form-control w-100" placeholder="Buscar..."/>
                                    </div>
                                </div>
                                <table class="table table-hover table-rounded align-middle table-row-bordered border mb-0 gy-5 gs-5">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th @if ($sort_nombre == 'id_inscripcion')
                                                    @if ($sort_direccion == 'asc')
                                                        class="table-sort-asc"
                                                    @else
                                                        class="table-sort-desc"
                                                    @endif
                                                @endif  style="cursor: pointer;" wire:click="ordenar_tabla('id_inscripcion')">
                                                #
                                            </th>
                                            <th @if ($sort_nombre == 'nombre_completo')
                                                    @if ($sort_direccion == 'asc')
                                                        class="table-sort-asc"
                                                    @else
                                                        class="table-sort-desc"
                                                    @endif
                                                @endif  style="cursor: pointer;" wire:click="ordenar_tabla('nombre_completo')">
                                                Apellidos y Nombres
                                            </th>
                                            <th>Numero Documento</th>
                                            <th @if ($sort_nombre == 'inscripcion_fecha')
                                                    @if ($sort_direccion == 'asc')
                                                        class="table-sort-asc"
                                                    @else
                                                        class="table-sort-desc"
                                                    @endif
                                                @endif  style="cursor: pointer;" wire:click="ordenar_tabla('inscripcion_fecha')">
                                                Fecha de Inscripcion
                                            </th>
                                            <th>Celular</th>
                                            <th>Correo Electronico</th>
                                            <th>Especialidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $contador = 1;
                                        @endphp
                                        @forelse ($inscripciones as $item)
                                            <tr class="fs-6">
                                                <td class="fw-bold">
                                                    {{ $contador++ }}
                                                </td>
                                                <td>
                                                    {{ $item->nombre_completo }}
                                                </td>
                                                <td>
                                                    {{ $item->numero_documento }}
                                                </td>
                                                <td>
                                                    {{ date('d/m/Y', strtotime($item->inscripcion_fecha)) }}
                                                </td>
                                                <td>
                                                    (+51) {{ $item->celular }}
                                                </td>
                                                <td>
                                                    {{ $item->correo }}
                                                </td>
                                                <td>
                                                    {{ $item->especialidad_carrera }}
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda "{{ $search }}"
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // filtro_proceso select2
        $(document).ready(function () {
            $('#filtro_proceso').select2({
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
            $('#filtro_proceso').on('change', function(){
                @this.set('filtro_proceso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_proceso').select2({
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
                $('#filtro_proceso').on('change', function(){
                    @this.set('filtro_proceso', this.value);
                });
            });
        });
    </script>
@endpush
