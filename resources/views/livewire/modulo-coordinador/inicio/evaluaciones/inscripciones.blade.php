<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    @if ($programa->mencion)
                        Mencion en {{ ucwords(strtolower($programa->mencion)) }} en Modalidad
                        {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de
                        {{ ucwords(strtolower(formatearAdmisionVisual($admision->admision))) }}
                    @else
                        {{ ucwords(strtolower($programa->programa)) }} en
                        {{ ucwords(strtolower($programa->subprograma)) }} en Modalidad
                        {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de
                        {{ ucwords(strtolower(formatearAdmisionVisual($admision->admision))) }}
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
                        <a href="{{ route('coordinador.inicio') }}"
                            class="text-muted text-hover-primary">Evaluaciones</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.programas', $programa->id_modalidad) }}"
                            class="text-muted text-hover-primary">Modalidad
                            {{ ucwords(strtolower($programa->modalidad->modalidad)) }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        @if ($programa->mencion)
                            Mencion en {{ ucwords(strtolower($programa->mencion)) }}
                        @else
                            {{ ucwords(strtolower($programa->programa)) }} en
                            {{ ucwords(strtolower($programa->subprograma)) }}
                        @endif
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    {{-- @if ($inscripciones->count() == $evaluaciones->count()) --}}
                        @if ($programa->programa_tipo == 1)
                            <a href="{{ route('coordinador.reporte-maestria', ['id_programa' => $id_programa, 'id_admision' => $id_admision]) }}"
                                target="_blank" class="btn btn-info fw-bold">
                                Generar Acta de Evaluación
                            </a>
                        @else
                            <a href="{{ route('coordinador.reporte-doctorado', ['id_programa' => $id_programa, 'id_admision' => $id_admision]) }}"
                                target="_blank" class="btn btn-info fw-bold">
                                Generar Acta de Evaluación
                            </a>
                        @endif
                    {{-- @else
                        <button type="button" class="btn btn-info fw-bold" disabled>
                            Generar Acta de Evaluación
                        </button>
                    @endif --}}
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
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column gap-2">
                            @if ($programa->programa_tipo == 1)
                                <span class="fw-bold fs-5">
                                    La nota aprobatoria para ser admitido es de
                                    {{ number_format($puntaje_model->puntaje_maestria) }} puntos totales (EVA.
                                    EXPEDIENTE + ENTREVISTA + TEMA DE TESIS). Una vez realizado la evaluación, no podrá
                                    realizar modificación de las notas ingresadas.
                                </span>
                            @else
                                <span class="fw-bold fs-5">
                                    La nota aprobatoria para ser admitido es de
                                    {{ number_format($puntaje_model->puntaje_doctorado) }} puntos totales (EVA.
                                    EXPEDIENTE + ENTREVISTA + TEMA DE TESIS). Una vez realizado la evaluación, no podrá
                                    realizar modificación de las notas ingresadas.
                                </span>
                            @endif
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="row g-3 mb-5">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="d-flex align-items-center gap-2">
                                                De <input type="text" class="form-control" wire:model="nombre_desde"
                                                    placeholder="Ingrese el nombre completo">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex align-items-center gap-2">
                                                Hasta <input type="text" class="form-control"
                                                    wire:model="nombre_hasta" placeholder="Ingrese el nombre completo">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn btn-info" wire:click="filtrar_nombre">
                                                Buscar
                                            </button>
                                            <button class="btn btn-secondary" wire:click="limpiar_filtro_nombre">
                                                Limpiar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input type="search" wire:model="search" class="form-control w-full"
                                        placeholder="Buscar..." />
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>#</th>
                                            <th>Apellidos y Nombres</th>
                                            <th>Documento</th>
                                            <th class="text-center">Eva. Expediente</th>
                                            @if ($programa->programa_tipo == 2)
                                                <th class="text-center">Eva. Tesis</th>
                                            @endif
                                            <th class="text-center">Eva. Entrevista</th>
                                            <th class="text-center">Puntaje Final</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($inscripciones as $item)
                                            @php $evaluacion = App\Models\Evaluacion::where('id_inscripcion', $item->id_inscripcion)->first(); @endphp
                                            <tr>
                                                <td class="fw-bold fs-6">
                                                    {{ $item->id_inscripcion }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->nombre_completo }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->numero_documento }}
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_expediente)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_expediente) }}
                                                                pts.
                                                            </span>
                                                        @else
                                                            <button class="btn btn-info hover-scale"
                                                                wire:click="evaluacion_expediente({{ $item->id_inscripcion }})">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-info hover-scale"
                                                            wire:click="evaluacion_expediente({{ $item->id_inscripcion }})">
                                                            Evaluar
                                                        </button>
                                                    @endif
                                                </td>
                                                @if ($programa->programa_tipo == 2)
                                                    <td align="center">
                                                        @if ($evaluacion)
                                                            @if ($evaluacion->puntaje_investigacion)
                                                                <span class="fw-bold fs-6">
                                                                    {{ number_format($evaluacion->puntaje_investigacion) }}
                                                                    pts.
                                                                </span>
                                                            @else
                                                                <button class="btn btn-info hover-scale"
                                                                    wire:click="evaluacion_investigacion({{ $item->id_inscripcion }})">
                                                                    Evaluar
                                                                </button>
                                                            @endif
                                                        @else
                                                            <button class="btn btn-info hover-scale"
                                                                wire:click="evaluacion_investigacion({{ $item->id_inscripcion }})">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_entrevista)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_entrevista) }}
                                                                pts.
                                                            </span>
                                                        @else
                                                            <button class="btn btn-info hover-scale"
                                                                wire:click="evaluacion_entrevista({{ $item->id_inscripcion }})">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-info hover-scale"
                                                            wire:click="evaluacion_entrevista({{ $item->id_inscripcion }})">
                                                            Evaluar
                                                        </button>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_final)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_final) }} pts.
                                                            </span>
                                                        @else
                                                            <span class="fw-bold fs-6">
                                                                -
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="fw-bold fs-6">
                                                            -
                                                        </span>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->evaluacion_estado == 1)
                                                            <span class="badge badge-warning fs-6 px-3 py-2">
                                                                Pendiente
                                                            </span>
                                                        @elseif ($evaluacion->evaluacion_estado == 2)
                                                            <span class="badge badge-success fs-6 px-3 py-2">
                                                                Admitido
                                                            </span>
                                                        @elseif ($evaluacion->evaluacion_estado == 3)
                                                            <span class="badge badge-danger fs-6 px-3 py-2">
                                                                No Admitido
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning fs-6">
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    <button class="btn btn-primary fw-bold hover-scale"
                                                        wire:click="detalle_evaluacion({{ $item->id_inscripcion }})"
                                                        data-bs-toggle="modal" data-bs-target="#detalle_evaluacion">
                                                        Detalle
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="{{ $programa->programa_tipo == 2 ? '9' : '8' }}"
                                                        class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda
                                                        "{{ $search }}"
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="{{ $programa->programa_tipo == 2 ? '9' : '8' }}"
                                                        class="text-center text-muted">
                                                        No hay registros
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{-- paginacion de la tabla --}}
                            @if ($inscripciones->hasPages())
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $inscripciones->firstItem() }} - {{ $inscripciones->lastItem() }}
                                        de {{ $inscripciones->total() }} registros
                                    </div>
                                    <div>
                                        {{ $inscripciones->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $inscripciones->firstItem() }} - {{ $inscripciones->lastItem() }}
                                        de {{ $inscripciones->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" tabindex="-1" id="detalle_evaluacion">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{ $title_modal }}</h2>

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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Apellidos y Nombres
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $nombre_completo }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Documento de Identidad
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $documento }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Correo Electrónico
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $correo }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Celular
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    (+51) {{ $celular }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Especialidad
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $especialidad }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Grado Académico
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $grado_academico }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div
                                class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5">
                                <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 d-flex align-items-center">
                                    <i class="las la-exclamation-circle fs-1 text-primary"></i>
                                </span>
                                <div class="d-flex flex-column gap-2">
                                    <span class="fw-bold fs-6">
                                        - Para abrir el expediente, dar click en el nombre del mismo. <br>
                                        - En caso de que el postulante no haya enviado su expediente, aparecerá en color
                                        rojo.
                                    </span>
                                </div>
                            </div>
                        </div>
                        <span class="col-12 fs-3 fw-bold mb-3">
                            Información de Expedientes
                        </span>
                        <div class="col-md-12">
                            <div class="row g-2">
                                @if ($expedientes)
                                    @foreach ($expedientes as $item)
                                        <div class="col-md-12">
                                            @if ($item->estado == 1)
                                                <a target="_blank"
                                                    href="{{ asset($item->expediente_inscripcion_url) }}">
                                                    <div
                                                        class="card hover-elevate-up bg-light-success border border-3 border-success px-5 py-2 text-gray-900">
                                                        <span class="fs-5 fw-semibold">
                                                            {{ $item->expediente }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @else
                                                <div
                                                    class="card bg-light-danger border border-3 border-danger px-5 py-2 text-gray-900">
                                                    <span class="fs-5 fw-semibold">
                                                        {{ $item->expediente }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
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
