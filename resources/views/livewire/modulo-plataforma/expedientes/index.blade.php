<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Expedientes</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Expedientes</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div class="m-0">
                    <a href="#"
                        class="btn btn-flex bg-body btn-color-gray-700 btn-active-color-primary shadow-sm fw-bold"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        Filtrar por Proceso de Admisión
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                        id="menu_expediente" wire:ignore.self>
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
                                    <select class="form-select" wire:model="filtro_proceso" id="filtro_proceso"
                                        data-control="select2" data-placeholder="Seleccione" data-hide-search="true">
                                        @foreach ($admisiones as $item)
                                            <option value="{{ $item->id_programa_proceso }}">
                                                {{ formatearAdmisionVisual($item->programa_proceso->admision->admision) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="mb-10">
                                <label class="form-label fw-semibold">Member Type:</label>
                                <div class="d-flex">
                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                        <span class="form-check-label">Author</span>
                                    </label>
                                    <label class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="2" checked="checked" />
                                        <span class="form-check-label">Customer</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-10">
                                <label class="form-label fw-semibold">Notifications:</label>
                                <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
                                    <label class="form-check-label">Enabled</label>
                                </div>
                            </div> --}}
                            <div class="d-flex justify-content-end">
                                <button type="button" wire:click="resetear_filtro"
                                    class="btn btn-sm btn-light btn-active-light-primary me-2"
                                    data-kt-menu-dismiss="true">Resetear</button>
                                <button type="submit" class="btn btn-sm btn-primary"
                                    data-kt-menu-dismiss="true">Aplicar</button>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create</a> --}}
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta de fecha de actualizacion de expedientes --}}
                    @if (!$admitido)
                        @if ($admision->admision_fecha_fin_inscripcion < date('Y-m-d'))
                            <div
                                class="alert bg-light-danger border border-3 border-danger d-flex align-items-center p-5 mb-5">
                                <i class="ki-outline ki-information-2 fs-2qx me-4 text-danger"></i>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-5">
                                        La fecha limite para actualizar sus expedientes ha expirado
                                    </span>
                                </div>
                            </div>
                        @else
                            <div
                                class="alert bg-light-warning border border-3 border-warning d-flex align-items-center p-5 mb-5">
                                <i class="ki-outline ki-information-2 fs-2qx me-4 text-warning"></i>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold fs-5">
                                        Recuerde que la fecha limite para actualizar sus expedientes es el
                                        {{ $fecha_fin_admision }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endif
                    {{-- alerta para que el usuario sepa de donde abrir los expedientes --}}
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                Nota: Para abrir los expedientes debe hacer click en el nombre de cada uno de los
                                expedientes
                            </span>
                        </div>
                    </div>
                    <div
                        class="alert bg-light-warning border border-3 border-warning d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-warning"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                Nota: Una vez evaluado su expediente, ya no podrá modificarlo
                            </span>
                        </div>
                    </div>
                    {{-- tabla de expedientes --}}
                    <div class="card shadow-sm mb-5">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                <thead class="bg-light-warning">
                                    <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                        <th>Expedientes</th>
                                        <th>Estado</th>
                                        <th>Verificación</th>
                                        <th class="col-md-3">Fecha de Entrega</th>
                                        @if ($admitido)
                                            @if ($mostrar_acciones_expediente == true)
                                                <th></th>
                                            @endif
                                        @else
                                            @php
                                                $evaluacion = App\Models\Evaluacion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
                                            @endphp
                                            @if (!$evaluacion)
                                                {{-- @if ($inscripcion->programa_proceso->admision->id_admision == $admision->id_admision)
                                                    @if ($admision->admision_fecha_fin_inscripcion >= date('Y-m-d')) --}}
                                                        <th></th>
                                                    {{-- @endif
                                                @endif --}}
                                            @endif
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $valor = 0; @endphp
                                    @foreach ($expedientes_model as $item2)
                                        @if ($expedientes)
                                            @foreach ($expedientes as $item)
                                                @if ($item2->id_expediente_admision == $item->id_expediente_admision)
                                                    <tr class="fs-6">
                                                        <td>
                                                            <a href="{{ asset($item->expediente_inscripcion_url) }}"
                                                                target="_blank" class="text-gray-800 fw-semibold">
                                                                {{ $item->expediente_admision->expediente->expediente }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-primary fs-6 px-3 py-2">
                                                                Entregado
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($item->expediente_inscripcion_verificacion == 0)
                                                                <span class="badge badge-warning fs-6 px-3 py-2">
                                                                    Pendiente
                                                                </span>
                                                            @elseif ($item->expediente_inscripcion_verificacion == 1)
                                                                <span class="badge badge-success fs-6 px-3 py-2">
                                                                    Verificado
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger fs-6 px-3 py-2">
                                                                    Observado
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ convertirFechaHora($item->expediente_inscripcion_fecha) }}
                                                        </td>
                                                        @if ($admitido)
                                                            @if ($mostrar_acciones_expediente == true)
                                                                <td class="text-end">
                                                                    <a href="#modal_expediente"
                                                                        wire:click="cargar_expediente_inscripcion({{ $item->id_expediente_inscripcion }})"
                                                                        class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modal_expediente">
                                                                        Editar
                                                                    </a>
                                                                </td>
                                                            @endif
                                                        @else
                                                            @if (!$evaluacion)
                                                                {{-- @if ($inscripcion->programa_proceso->admision->id_admision == $admision->id_admision)
                                                                    @if ($admision->admision_fecha_fin_inscripcion >= date('Y-m-d')) --}}
                                                                        <td class="text-end">
                                                                            <a href="#modal_expediente"
                                                                                wire:click="cargar_expediente_inscripcion({{ $item->id_expediente_inscripcion }})"
                                                                                class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modal_expediente">
                                                                                Editar
                                                                            </a>
                                                                        </td>
                                                                    {{-- @endif
                                                                @endif --}}
                                                            @endif
                                                        @endif
                                                    </tr>
                                                    @php $valor = 1; @endphp
                                                @endif
                                            @endforeach
                                            @if ($expedientes->count() == 0)
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No hay
                                                        expedientes registrados</td>
                                                </tr>
                                            @endif
                                            @if ($valor == 0)
                                                <tr class="fs-6">
                                                    <td class="text-gray-800 fw-semibold">
                                                        @php $expediente = App\Models\Expediente::find($item2->id_expediente); @endphp
                                                        {{ $expediente->expediente }}
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger fs-6 px-3 py-2">No
                                                            Entregado</span>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        Sin fecha
                                                    </td>
                                                    @if ($admitido)
                                                        @if ($mostrar_acciones_expediente == true)
                                                            <td class="text-end">
                                                                <a href="#modal_expediente"
                                                                    wire:click="cargar_expediente({{ $item2->id_expediente_admision }})"
                                                                    class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modal_expediente">
                                                                    Agregar
                                                                </a>
                                                            </td>
                                                        @endif
                                                    @else
                                                        @if (!$evaluacion)
                                                            {{-- @if ($inscripcion->programa_proceso->admision->id_admision == $admision->id_admision)
                                                                @if ($admision->admision_fecha_fin_inscripcion >= date('Y-m-d')) --}}
                                                                    <td class="text-end">
                                                                        <a href="#modal_expediente"
                                                                            wire:click="cargar_expediente({{ $item2->id_expediente_admision }})"
                                                                            class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#modal_expediente">
                                                                            Agregar
                                                                        </a>
                                                                    </td>
                                                                {{-- @endif
                                                            @endif --}}
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endif
                                        @endif
                                        @php $valor = 0; @endphp
                                    @endforeach
                                    @if (count($expedientes_model) == 0)
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No hay expedientes
                                                registrados</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal create/edit expediente --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_expediente">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $titulo_modal }}
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" wire:click="limpiar_expediente"
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
                    <form autocomplete="off">
                        <div class="mb-5">
                            <label for="expediente" class="required form-label">
                                {{ $expediente_nombre }}
                            </label>
                            <input type="file" wire:model="expediente"
                                class="form-control mb-1 @error('expediente') is-invalid @enderror" accept=".pdf"
                                id="upload{{ $iteration }}" />
                            <span class="text-muted">
                                Nota: El archivo debe ser en formato PDF <br>
                            </span>
                            @error('expediente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="limpiar_expediente">
                        Cerrar
                    </button>
                    <button type="button" wire:click="registrar_expediente" class="btn btn-primary"
                        @if ($expediente == null) disabled @endif wire:loading.attr="disabled">
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
    </div>
</div>
@push('scripts')
    <script>
        // filtro_proceso select2
        $(document).ready(function() {
            $('#filtro_proceso').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                minimumResultsForSearch: Infinity,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#filtro_proceso').on('change', function() {
                @this.set('filtro_proceso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_proceso').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    minimumResultsForSearch: Infinity,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#filtro_proceso').on('change', function() {
                    @this.set('filtro_proceso', this.value);
                });
            });
        });
    </script>
@endpush
