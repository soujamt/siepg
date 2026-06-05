<div class="col-md-12 mb-md-5 mb-xl-10">
    {{-- alerta para ver la fecha de resultados de admitidos --}}
    @if ($admision->admision_fecha_resultados <= today())
        @if ($admitido)
            {{-- alerta de admitido --}}
            <div class="alert alert-dismissible bg-light-success border border-3 border-success d-flex flex-center flex-column py-10 px-10 px-lg-20">
                <i class="ki-outline ki-like fs-5tx text-success mb-5"></i>

                <div class="text-center">
                    <h1 class="fw-bold mb-5">
                        ¡Fuiste admitido en el Proceso de
                        {{ ucwords(strtolower(formatearAdmisionVisual($inscripcion_admision->admision->admision))) }}!
                    </h1>

                    <div class="separator separator-dashed border-success opacity-25 mb-5"></div>

                    <div class="text-gray-900 fs-5">
                        <strong>
                            Estimado(a) postulante, se le informa que ha sido admitido al PROGRAMA DE
                            @if ($admitido->programa_proceso->programa_plan->programa->mencion)
                                {{ $admitido->programa_proceso->programa_plan->programa->programa }} EN
                                {{ $admitido->programa_proceso->programa_plan->programa->subprograma }} CON MENCION EN
                                {{ $admitido->programa_proceso->programa_plan->programa->mencion }}
                            @else
                                {{ $admitido->programa_proceso->programa_plan->programa->programa }} EN
                                {{ $admitido->programa_proceso->programa_plan->programa->subprograma }}
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
            {{-- alerta de observaciones de la evaluacion --}}
            <div class="alert bg-body border border-3 border-primary d-flex align-items-center p-5 mb-5">
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-3">
                        Observaciones de la evaluación
                    </span>
                    <span class="mt-2 fs-5">
                        {{ $evaluacion->evaluacion_observacion ? '- ' . $evaluacion->evaluacion_observacion : '- No cuenta con observaciones en su evaluación.' }}
                    </span>
                </div>
            </div>
        @else
            @if ($evaluacion)
                @if ($evaluacion->evaluacion_estado == 1 || $evaluacion->evaluacion_estado == 2)
                    {{-- alerta de fecha de resultados de admitidos --}}
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-4">
                                Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
                            </span>
                        </div>
                    </div>
                @elseif ($evaluacion->evaluacion_estado == 3)
                    {{-- alerta de no admitido --}}
                    <div class="alert bg-light-danger border border-3 border-danger d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-dislike fs-2qx me-4 text-danger"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-4">
                                No fuiste admitido en el Proceso de
                                {{ ucwords(strtolower(formatearAdmisionVisual($inscripcion_admision->admision->admision))) }}
                            </span>
                        </div>
                    </div>
                    {{-- alerta de observaciones de la evaluacion --}}
                    <div class="alert bg-body border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-3">
                                Observaciones de la evaluación
                            </span>
                            <span class="mt-2 fs-5">
                                {{ $evaluacion->evaluacion_observacion ? '- ' . $evaluacion->evaluacion_observacion : '- No cuenta con observaciones en su evaluación.' }}
                            </span>
                        </div>
                    </div>
                @endif
            @else
                {{-- alerta de fecha de resultados de admitidos --}}
                <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                    <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                    <div class="d-flex flex-column">
                        <span class="fw-bold fs-4">
                            Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
                        </span>
                    </div>
                </div>
            @endif
        @endif
    @else
        {{-- alerta de fecha de resultados de admitidos --}}
        <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
            <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
            <div class="d-flex flex-column">
                <span class="fw-bold fs-4">
                    Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
                </span>
            </div>
        </div>
    @endif
    {{-- card de estado de inscripcion puntajes --}}
    <div class="row g-5 mb-5">
        {{-- <div class="col-sm-12 col-md-6 col-lg-6 @if ($inscripcion_ultima->inscripcion_tipo_programa == 1) col-xl-6 @elseif ($inscripcion_ultima->inscripcion_tipo_programa == 2) col-xl-4 @endif">
            <div class="card card-body shadow-sm">
                <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                    Evaluación de Expedientes
                </span>
                @if ($inscripcion_admision->admision->admision_fecha_resultados <= today())
                    @if ($evaluacion)
                    <span class="fs-3 text-gray-800 text-center py-2">
                        Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->puntaje_expediente) }}</span> pts.
                    </span>
                    @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                    @endif
                @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                @endif
            </div>
        </div>
        @if ($inscripcion_ultima->inscripcion_tipo_programa == 2)
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                <div class="card card-body shadow-sm">
                    <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                        Evaluación de Investigación
                    </span>
                    @if ($inscripcion_admision->admision->admision_fecha_resultados <= today())
                        @if ($evaluacion)
                            <span class="fs-3 text-gray-800 text-center py-2">
                                Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->puntaje_investigacion) }}</span> pts.
                            </span>
                        @else
                            <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                                Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                            </span>
                        @endif
                    @else
                        <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                            Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
        <div class="col-sm-12 col-md-6 col-lg-6 @if ($inscripcion_ultima->inscripcion_tipo_programa == 1) col-xl-6 @elseif ($inscripcion_ultima->inscripcion_tipo_programa == 2) col-xl-4 @endif">
            <div class="card card-body shadow-sm">
                <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                    Evaluación de Entrevista
                </span>
                @if ($inscripcion_admision->admision->admision_fecha_resultados <= today())
                    @if ($evaluacion)
                    <span class="fs-3 text-gray-800 text-center py-2">
                        Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->puntaje_entrevista) }}</span> pts.
                    </span>
                    @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                    @endif
                @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                @endif
            </div>
        </div> --}}
    </div>
    {{-- card de ficha de inscripcion, prospecto de admision y expedientes --}}
    <div class="row g-5 mb-5">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-15 py-10 rounded-4 mx-auto mb-5">
                    <i class="ki-outline ki-document fs-5tx text-info"></i>
                </div>
                <h2 class="card-title mb-5 text-center">
                    Ficha de Inscripción
                </h2>
                <a target="_blank" href="{{ asset($inscripcion_ultima->inscripcion_ficha_url) }}" class="btn btn-info">
                    Descargar
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-15 py-10 rounded-4 mx-auto mb-5">
                    <i class="ki-outline ki-document fs-5tx text-info"></i>
                </div>
                <h2 class="card-title mb-5 text-center">
                    Prospecto de Admisión
                </h2>
                {{-- <a target="_blank" href="{{ asset('assets_pdf/prospecto-admision-2024.pdf') }}"
                    class="btn btn-info">
                    Descargar
                </a> --}}
                <button
                    type="button"
                    class="btn btn-info"
                    disabled
                >
                    Descargar
                </button>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-15 py-10 rounded-4 mx-auto mb-5">
                    <i class="ki-outline ki-folder fs-5tx text-info"></i>
                </div>
                <h2 class="card-title mb-5 text-center">
                    Expedientes
                </h2>
                <a href="{{ route('plataforma.expediente') }}" type="button" class="btn btn-info">
                    Ver detalle
                </a>
            </div>
        </div>
    </div>
</div>
