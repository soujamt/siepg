<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Matriculados - {{ $curso->curso_nombre }} - Ciclo {{ $curso->ciclo->ciclo }} - Grupo {{ $grupo->grupo_detalle }}
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('docente.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Matriculados
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    @if ($mostrar_acta)
                        <button type="button" class="btn btn-info" wire:click="descargar_actas"
                            wire:loading.attr="disabled" wire:target="generar_acta_notas">
                            Descargar Acta de Evaluación
                        </button>
                    @endif
                    <button type="button"
                        wire:click="generar_acta_notas"
                        class="btn {{ $mostrar_acta ? 'btn-light-info' : 'btn-info' }} fw-bold"
                        @if ($matriculados_count != $matriculados_finalizados_count) disabled @endif
                        wire:loading.attr="disabled"
                        wire:target="generar_acta_notas">
                        <span wire:loading.remove wire:target="generar_acta_notas">
                            {{ $mostrar_acta ? 'Volver a generar acta' : 'Generar Acta de Evaluación' }}
                        </span>
                        <span wire:loading wire:target="generar_acta_notas">
                            Generando <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <button type="button"
                        wire:click="exportar_excel_lista_matriculados"
                        class="btn btn-success fw-bold"
                        >
                        Lista de Matriculados (Excel)
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div class="alert bg-light-primary border border-4 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-3">
                                A continuación se muestran los alumnos matriculados en el curso.
                            </span>
                        </div>
                    </div>
                    <div class="alert bg-light-warning border border-4 border-warning d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-2 fs-2qx me-4 text-warning"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-3">
                                @if ($docente_curso->docente_curso_estado == 2)
                                    El curso ya ha sido finalizado.
                                @else
                                    Recuerde que una vez ingresadas las notas, no podrá modificarlas.
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="alert bg-light-info border border-4 border-info d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-info"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-3">
                                Recomendaciones:
                            </span>
                            <span class="fs-4">
                                - La nota minima para aprobar el curso es tener un promedio final de 14. (Tomar sus precauciones)
                            </span>
                        </div>
                    </div>
                    {{-- card  --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="row mb-5 g-5">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    @if ($docente_curso->docente_curso_estado != 2)
                                        <div class="d-flex flex-lg-row flex-column gap-3 justify-content-end">
                                            @if ($modo == 'show')
                                                <button type="button" wire:click="modo_cancelar" class="btn btn-danger w-lg-50">
                                                    Cancelar
                                                </button>
                                                <button type="button" wire:click="guardar_notas" class="btn btn-primary w-lg-50">
                                                    Registrar Notas
                                                </button>
                                            @else
                                                <button type="button" wire:click="modo_ingresar_notas" class="btn btn-success w-lg-60">
                                                    Habilitar Ingreso de Notas
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <input type="search" wire:model="search" class="form-control w-100" placeholder="Buscar..."/>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-rounded align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>Nro</th>
                                            <th>Código</th>
                                            <th>Alumno</th>
                                            <th class="text-center">Evaluación Permanente</th>
                                            <th class="text-center">Evaluación Medio Curso</th>
                                            <th class="text-center">Evaluación Final</th>
                                            <th class="text-center">Promedio Final</th>
                                            <th class="text-center">Estado</th>
                                            @if ($docente_curso->docente_curso_estado != 2)
                                                <th class="text-center">Acciones</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($matriculados as $item)
                                            <tr class="fs-6 text-gray-800" wire:key="{{ $item->id_matricula_curso }}">
                                                <td class="fw-bold">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ $item->matricula->admitido->admitido_codigo }}
                                                </td>
                                                <td>
                                                    {{ $item->matricula->admitido->persona->nombre_completo }}
                                                </td>
                                                <td align="center">
                                                    @if ($item->nota_evaluacion_permanente !== null)
                                                        <span class="fw-bold fs-5">
                                                            {{ number_format($item->nota_evaluacion_permanente) }} pts.
                                                        </span>
                                                    @else
                                                        @if ($modo == 'hide')
                                                            -
                                                        @elseif ($modo == 'show')
                                                            <input type="number" class="form-control w-100px @error('notas') is-invalid @enderror @error('notas.' . $item->id_matricula_curso . '.nota1') is-invalid @enderror @if($this->esNotaValida($item->id_matricula_curso, 'nota3')) is-valid @endif" wire:model.lazy="notas.{{ $item->id_matricula_curso }}.nota1">
                                                        @endif
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($item->nota_evaluacion_medio_curso !== null)
                                                        <span class="fw-bold fs-5">
                                                            {{ number_format($item->nota_evaluacion_medio_curso) }} pts.
                                                        </span>
                                                    @else
                                                        @if ($modo == 'hide')
                                                            -
                                                        @elseif ($modo == 'show')
                                                            <input type="number" class="form-control w-100px @error('notas') is-invalid @enderror @error('notas.' . $item->id_matricula_curso . '.nota2') is-invalid @enderror @if($this->esNotaValida($item->id_matricula_curso, 'nota3')) is-valid @endif" wire:model.lazy="notas.{{ $item->id_matricula_curso }}.nota2">
                                                        @endif
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($item->nota_evaluacion_final !== null)
                                                        <span class="fw-bold fs-5">
                                                            {{ number_format($item->nota_evaluacion_final) }} pts.
                                                        </span>
                                                    @else
                                                        @if ($modo == 'hide')
                                                            -
                                                        @elseif ($modo == 'show')
                                                            <input type="number" class="form-control w-100px @error('notas') is-invalid @enderror @error('notas.' . $item->id_matricula_curso . '.nota3') is-invalid @enderror @if($this->esNotaValida($item->id_matricula_curso, 'nota3')) is-valid @endif" wire:model.lazy="notas.{{ $item->id_matricula_curso }}.nota3">
                                                        @endif
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($item->nota_promedio_final !== null)
                                                        <span class="fw-bold fs-5">
                                                            {{ number_format($item->nota_promedio_final) }} pts.
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($item->estado == 2)
                                                        <span class="badge badge-success fs-6 px-3 py-2">
                                                            Aprobado
                                                        </span>
                                                    @elseif ($item->estado == 0)
                                                        <span class="badge badge-danger fs-6 px-3 py-2">
                                                            Desaprobado
                                                        </span>
                                                    @elseif ($item->estado == 3)
                                                        <span class="badge badge-danger fs-6 px-3 py-2">
                                                            Nsp
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning fs-6 px-3 py-2">
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                @if ($docente_curso->docente_curso_estado != 2)
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-flex btn-center fw-bold btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                        {{-- data-kt-menu-trigger="click" --}}
                                                        {{-- data-kt-menu-placement="bottom-end" --}}
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
                                                            <a wire:click="alerta_asignar_nsp({{ $item->id_matricula_curso }})"
                                                                class="menu-link px-3 fs-6">
                                                                Asignar NSP
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda "{{ $search }}"
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
