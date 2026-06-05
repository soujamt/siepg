<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Programas en Modalidad {{ ucwords(strtolower($modalidad->modalidad)) }}</h1>
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
                    <li class="breadcrumb-item text-muted">Programas</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div class="m-0">
                    <a href="#" class="btn btn-flex bg-body btn-color-gray-700 btn-active-color-primary shadow-sm fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-3 svg-icon-muted me-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                            </svg>
                        </span>
                        Filtrar por Proceso de Admisión
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="menu_expediente" wire:ignore.self>
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">
                                Opciones de filtrado
                            </div>
                        </div>
                        <div class="separator border-gray-200"></div>
                        <form class="px-7 py-5" wire:submit.prevent="aplicar_filtro">
                            <div class="mb-10">
                                <label class="form-label fw-semibold">Proceso de Admisión:</label>
                                <div>
                                    <select class="form-select" wire:model="filtro_proceso" id="filtro_proceso"  data-control="select2" data-placeholder="Seleccione">
                                        @foreach ($admisiones as $item)
                                        <option value="{{ $item->id_admision }}">{{ formatearAdmisionVisual($item->admision) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" wire:click="resetear_filtro" class="btn btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Resetear</button>
                                <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true">Aplicar</button>
                            </div>
                        </form>
                    </div>
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
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                Los datos mostrados en esta sección son referentes a la evaluación de los postulantes por proceso de admisión.
                            </span>
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="row g-5 mb-5">
                        @foreach ($programas as $item)
                            @php
                                $inscritos = App\Models\Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                    ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                    ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                    ->where('programa.id_programa', $item->id_programa)
                                    ->where('programa.id_modalidad', $modalidad->id_modalidad)
                                    ->where('programa_proceso.id_admision', $proceso)
                                    ->where('inscripcion.inscripcion_estado', 1)
                                    ->count();
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm" style="height: 300px">
                                    <div class="h-100 px-6 py-4 d-flex flex-column justify-content-center aling-items-between">
                                        <div class="mb-5 text-center">
                                            <span class="fs-2 text-gray-800 fw-bold">
                                                @if ($item->mencion)
                                                    MENCION EN {{ $item->mencion }}
                                                @else
                                                    {{ $item->programa }} EN {{ $item->subprograma }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="mb-5 text-center">
                                            <span class="fs-1" style="font-weight: 700;">
                                                {{ $inscritos }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column row-gap-5">
                                            <a href="{{ route('coordinador.evaluaciones', ['id' => $item->id_programa, 'id_admision' => $proceso]) }}" class="btn btn-info btn-sm w-100">
                                                Evaluaciones
                                            </a>
                                            <a href="{{ route('coordinador.inscripciones', ['id' => $item->id_programa, 'id_admision' => $proceso]) }}" class="btn btn-secondary btn-sm w-100">
                                                Ver Inscritos
                                            </a>
                                            <button type="button" wire:click="descargar_admitidos({{ $item->id_programa }})" class="btn btn-dark btn-sm w-100">
                                                Descargar Admitidos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
