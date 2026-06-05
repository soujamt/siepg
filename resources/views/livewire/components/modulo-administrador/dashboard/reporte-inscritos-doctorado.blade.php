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
                        <th class="text-center col-md-1">#</th>
                        <th>Programa</th>
                        <th class="col-md-2 text-center">Cantidad</th>
                        <th class="col-md-2 text-center text-primary">Verificados</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programas_doctorado as $item)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                @if ($item->mencion)
                                    Mencion en {{ ucwords(strtolower($item->mencion)) }}
                                @else
                                    {{ ucwords(strtolower($item->programa)) }} en {{ ucwords(strtolower($item->subprograma)) }}
                                @endif
                            </td>
                            <td class="fw-bold text-center">
                                {{ $item->cantidad }}
                            </td>
                            <td class="fw-bold text-center text-primary">
                                {{ $item->verificados }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
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
                    <td class="fw-bold text-center">
                        {{ $programas_doctorado->sum('cantidad') }}
                    </td>
                    <td class="fw-bold text-center text-primary">
                        {{ $programas_doctorado->sum('verificados') }}
                    </td>
                </tfoot>
            </table>
        </div>
    </div>
</div>
