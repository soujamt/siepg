<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Inicio</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('contable.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Inicio</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div class="m-0">
                    <a href="#" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary shadow-sm fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
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
                                <button type="button" wire:click="resetear_filtro" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Resetear</button>
                                <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Aplicar</button>
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
                        <i class="ki-duotone ki-information-5 fs-2qx me-4 text-primary">
                            <i class="path1"></i>
                            <i class="path2"></i>
                            <i class="path3"></i>
                        </i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                El dato mostrado de Inscripción es el total del proceso de admisión actual.
                            </span>
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="row g-5 mb-5">
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="px-6 py-4 mb-0">
                                    <div class="mb-3">
                                        <span class="fs-2" style="font-weight: 700;">
                                            Ingreso Total
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-semibold fs-1">
                                            S/. {{ number_format($ingreso_total, 2, ',', ' ') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="px-6 py-4 mb-0">
                                    <div class="mb-3">
                                        <span class="fs-2" style="font-weight: 700;">
                                            Registro Pagos
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-semibold fs-1">
                                            {{ $registro_total }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="px-6 py-4 mb-0">
                                    <div class="mb-3">
                                        <span class="fs-2" style="font-weight: 700;">
                                            Inscripciones
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-semibold fs-1">
                                            S/. {{ number_format($inscripcion_total, 2, ',', ' ') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="px-6 py-4 mb-0">
                                    <div class="mb-3">
                                        <span class="fs-2" style="font-weight: 700;">
                                            Constancia Ingreso
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-semibold fs-1">
                                            S/. {{ number_format($constancia_total, 2, ',', ' ') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="px-6 py-4 mb-0">
                                    <div class="mb-3">
                                        <span class="fs-2" style="font-weight: 700;">
                                            Matriculas
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-semibold fs-1">
                                            S/. {{ number_format($matricula_total, 2, ',', ' ') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="row g-5">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light-success">
                                    <h3 class="card-title fw-bold">
                                        Reporte de Inscritos por Programa de Maestría del Proceso de {{ ucwords(strtolower(formatearAdmisionVisual($admision->admision))) }}
                                    </h3>
                                </div>
                                <div class="card-body mb-0">
                                    <div class="table-responsive" wire:loading.class="table-loading" wire:target="aplicar_filtro">
                                        <div class="table-loading-message">
                                            Cargando...
                                        </div>
                                        <table class="table table-hover table-rounded table-row-bordered border mb-0 gy-4 gs-4" wire:loading.class="opacity-25" wire:target="aplicar_filtro">
                                            <thead>
                                                <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                                    <th>#</th>
                                                    <th>Programa</th>
                                                    <th class="col-md-2">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($programas_maestria as $item)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            @if ($item->mencion)
                                                                Mencion en {{ ucwords(strtolower($item->mencion)) }}
                                                            @else
                                                                {{ ucwords(strtolower($item->programa)) }} en {{ ucwords(strtolower($item->subprograma)) }}
                                                            @endif
                                                        </td>
                                                        <td class="fw-bold">
                                                            {{ $item->cantidad }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            No hay registros
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot class="bg-light-secondary">
                                                <td colspan="2" class="text-end">
                                                    <span class="fw-bold">
                                                        Total
                                                    </span>
                                                </td>
                                                <td class="fw-bold">
                                                    {{ $programas_maestria->sum('cantidad') }}
                                                </td>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light-primary">
                                    <h3 class="card-title fw-bold">
                                        Reporte de Inscritos por Programa de Doctorado del Proceso de {{ ucwords(strtolower(formatearAdmisionVisual($admision->admision))) }}
                                    </h3>
                                </div>
                                <div class="card-body mb-0">
                                    <div class="table-responsive" wire:loading.class="table-loading" wire:target="aplicar_filtro">
                                        <div class="table-loading-message">
                                            Cargando...
                                        </div>
                                        <table class="table table-hover table-rounded table-row-bordered border mb-0 gy-4 gs-4" wire:loading.class="opacity-25" wire:target="aplicar_filtro">
                                            <thead>
                                                <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                                    <th>#</th>
                                                    <th>Programa</th>
                                                    <th class="col-md-2">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($programas_doctorado as $item)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            @if ($item->mencion)
                                                                Mencion en {{ ucwords(strtolower($item->mencion)) }}
                                                            @else
                                                                {{ ucwords(strtolower($item->programa)) }} en {{ ucwords(strtolower($item->subprograma)) }}
                                                            @endif
                                                        </td>
                                                        <td class="fw-bold">
                                                            {{ $item->cantidad }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            No hay registros
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot class="bg-light-secondary">
                                                <td colspan="2" class="text-end">
                                                    <span class="fw-bold">
                                                        Total
                                                    </span>
                                                </td>
                                                <td class="fw-bold">
                                                    {{ $programas_doctorado->sum('cantidad') }}
                                                </td>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal create/edit expediente --}}
    {{-- <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_expediente">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $titulo_modal }}
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar_expediente">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="mb-5">
                            <label for="expediente" class="required form-label">
                                {{ $expediente_nombre }}
                            </label>
                            <input type="file" wire:model="expediente" class="form-control mb-1 @error('expediente') is-invalid @enderror" accept=".pdf" id="upload{{ $iteration }}"/>
                            <span class="text-muted">
                                Nota: El archivo debe ser en formato PDF y no debe pesar mas de 10MB <br>
                            </span>
                            @error('expediente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar_expediente">
                        Cerrar
                    </button>
                    <button type="button" wire:click="registrar_expediente" class="btn btn-primary" @if($expediente == null) disabled @endif wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target="registrar_expediente">
                            {{ $boton_modal }}
                        </div>
                        <div wire:loading wire:target="registrar_expediente">
                            Procesando...
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
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
