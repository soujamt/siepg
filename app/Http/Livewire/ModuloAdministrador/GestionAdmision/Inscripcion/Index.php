<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\Inscripcion;

use App\Exports\reporte\moduloAdministrador\inscripcion\listaInscripcionesExport;
use App\Jobs\ObservarInscripcionJob;
use App\Jobs\ProcessRegistroFichaInscripcion2;
use App\Models\Admision;
use App\Models\Admitido;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\Modalidad;
use App\Models\Programa;
use App\Models\ProgramaPlan;
use App\Models\ProgramaProceso;
use App\Models\TipoSeguimiento;
use App\Models\UsuarioEstudiante;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; //paginacion de bootstrap

    protected $queryString = [
        'search' => ['except' => ''],
        'modalidadFiltro' => ['except' => ''],
        'procesoFiltro' => ['except' => ''],
        'programaFiltro' => ['except' => ''],
        'seguimientoFiltro' => ['except' => ''],
        'estadoFiltro' => ['except' => ''],
        'estado_filtro' => ['except' => ''],
        'estado_expediente_filtro' => ['except' => 'all'],
        'cant_paginas' => ['except' => 50],
    ];

    public $search = '';

    public $cant_paginas = 50;

    //Variables para el filtro de Inscripión
    public $procesoFiltro; //Para la búsqueda de inscripciones por proceso
    public $proceso_filtro; //Para el filtro de inscripciones por proceso
    public $modalidadFiltro; //Para la búsqueda de inscripciones por modalidad
    public $modalidad_filtro; //Para el filtro de inscripciones por modalidad
    public $programaFiltro; //Para la búsqueda de inscripciones por programa
    public $programa_filtro; //Para el filtro de inscripciones por programa
    public $seguimientoFiltro; //Para la búsqueda de inscripciones por seguimiento
    public $seguimiento_filtro; //Para el filtro de inscripciones por seguimiento
    public $mesFiltro;
    public $mes_filtro;
    public $estadoFiltro;
    public $estado_filtro;
    //variables
    public $id_inscripcion;
    public $modalidad;
    public $programa;
    public Collection $programasModal; //Para mostrar los programas en el modal

    // expeidentes de la inscripcion
    public $expedientes = [];
    public $expediente_postulante; //Datos de la inscripción en el modal de expedientes
    public $expediente_programa;

    // estado de la inscripcion
    public $estado;
    public $observacion_inscripcion;

    // filtro de estado de expediente
    public $estado_expediente_filtro = "all";

    // traslado de la inscripcion al proceso activo
    public $programa_proceso_traslado;
    public $programasTraslado = []; //Para mostrar los programas del proceso activo en el modal
    public $traslado_postulante;
    public $traslado_inscripcion;
    public $traslado_programa;

    //Para mapear el mes al filtrar
    public $meses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Setiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    protected $listeners = [
        'render',
        'cambiarEstado',
        'cambiarSeguimiento',
        'reservarPago',
        'eliminar',
        'reservar_inscripcion',
    ];

    public function mount()
    {
        $this->programasModal = new Collection();
        $this->proceso_filtro = Admision::query()
            ->where('admision_estado', 1)
            ->first()->id_admision;
        $this->procesoFiltro = $this->proceso_filtro;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'id_inscripcion' => 'required',
            'modalidad' => 'required',
            'programa' => 'required',
        ]);
    }

    // al buscar o cambiar los filtros directos volvemos a la primera pagina
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstadoExpedienteFiltro()
    {
        $this->resetPage();
    }

    public function updatingCantPaginas()
    {
        $this->resetPage();
    }

    // al cambiar la modalidad en el modal de editar programa se cargan sus programas del proceso activo
    public function updatedModalidad($modalidad)
    {
        $this->programa = null;
        $this->programasModal = $this->programasProcesoActivo($modalidad);
    }

    public function limpiar()
    {
        $this->reset(
            'id_inscripcion',
            'estado',
        );
    }

    //Limpiamos los filtros
    public function resetear_filtro()
    {
        $this->reset(
            'procesoFiltro',
            'programaFiltro',
            'seguimientoFiltro',
            'modalidadFiltro',
            'mesFiltro',
            'proceso_filtro',
            'programa_filtro',
            'seguimiento_filtro',
            'modalidad_filtro',
            'mes_filtro',
            'estadoFiltro',
            'estado_filtro',
        );
        $this->mount();
        $this->resetPage();
    }

    //Asignamos los filtros
    public function filtrar()
    {
        $this->procesoFiltro = $this->proceso_filtro ?? null;
        $this->modalidadFiltro = $this->modalidad_filtro ?? null;
        $this->programaFiltro = $this->programa_filtro ?? null;
        $this->seguimientoFiltro = $this->seguimiento_filtro ?? null;
        $this->mesFiltro = $this->mes_filtro ?? null;
        $this->estadoFiltro = $this->estado_filtro ?? null;
        $this->resetPage();
    }

    //Quitamos un filtro aplicado desde su etiqueta
    public function quitar_filtro($filtro)
    {
        switch ($filtro) {
            case 'proceso':
                $this->reset('procesoFiltro', 'proceso_filtro', 'mesFiltro', 'mes_filtro');
                break;
            case 'modalidad':
                $this->reset('modalidadFiltro', 'modalidad_filtro');
                break;
            case 'programa':
                $this->reset('programaFiltro', 'programa_filtro');
                break;
            case 'seguimiento':
                $this->reset('seguimientoFiltro', 'seguimiento_filtro');
                break;
            case 'mes':
                $this->reset('mesFiltro', 'mes_filtro');
                break;
            case 'estado':
                $this->reset('estadoFiltro', 'estado_filtro');
                break;
            case 'estado_expediente':
                $this->reset('estado_expediente_filtro');
                break;
            case 'search':
                $this->reset('search');
                break;
        }
        $this->resetPage();
    }

    //Alerta de confirmacion
    public function alertaConfirmacion($title, $text, $icon, $confirmButtonText, $cancelButtonText, $confimrColor, $cancelColor, $metodo, $id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'cancelButtonText' => $cancelButtonText,
            'confimrColor' => $confimrColor,
            'cancelColor' => $cancelColor,
            'metodo' => $metodo,
            'id' => $id,
        ]);
    }

    //Alertas de exito o error
    public function alertaInscripcion($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-inscripcion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Notificacion breve que no interrumpe (para acciones dentro de los modales)
    public function toastInscripcion($icon, $title)
    {
        $this->dispatchBrowserEvent('toast-inscripcion', [
            'icon' => $icon,
            'title' => $title,
        ]);
    }

    //Ejecutamos los cambios en una transaccion: si algo falla se revierte todo y se muestra un mensaje en lugar del error
    private function ejecutar_transaccion(callable $cambios, $mensaje_error)
    {
        try {
            DB::transaction($cambios);
            return true;
        } catch (\Throwable $e) {
            report($e);
            $this->alertaInscripcion(
                '¡Error!',
                $mensaje_error . ' No se guardó ningún cambio. Intente nuevamente y, si el problema continúa, comuníquese con soporte.',
                'error',
                'Aceptar',
                'danger'
            );
            return false;
        }
    }

    //Enviamos el correo de la inscripcion, si falla el cambio ya realizado no se revierte
    private function enviar_correo_inscripcion($id_inscripcion, $tipo)
    {
        try {
            ObservarInscripcionJob::dispatch($id_inscripcion, $tipo);
            return true;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

    //Programas del proceso activo, opcionalmente de una modalidad
    private function programasProcesoActivo($modalidad = null)
    {
        $admision = getAdmision();
        if (!$admision) {
            return new Collection();
        }

        return Programa::whereIn('id_programa', ProgramaPlan::join('programa_proceso', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                ->where('programa_proceso.id_admision', $admision->id_admision)
                ->where('programa_proceso.programa_proceso_estado', 1)
                ->select('programa_plan.id_programa'))
            ->when($modalidad, function ($query) use ($modalidad) {
                $query->where('id_modalidad', $modalidad);
            })
            ->orderBy('programa_tipo')
            ->orderBy('programa')
            ->orderBy('subprograma')
            ->get();
    }

    //Mostar modal de confirmacion para cambiar el estado del programa
    public function cargarAlertaEstado(Inscripcion $inscripcion)
    {
        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea cambiar el estado de la inscripción de ' . $inscripcion->persona->nombre_completo . '?', 'question', 'Modificar', 'Cancelar', 'primary', 'danger', 'cambiarEstado', $inscripcion->id_inscripcion);
    }

    //Cambiar el estado de la inscripción
    public function cambiarEstado($id)
    {
        $inscripcion = Inscripcion::find($id);
        $guardado = $this->ejecutar_transaccion(function () use ($inscripcion) {
            if ($inscripcion->inscripcion_estado == 1) { //Si el estado es activo(1), se cambia a inactivo(0)
                $inscripcion->inscripcion_estado = 0;
            } else { //Si el estado es inactivo(0), se cambia a activo(1)
                $inscripcion->inscripcion_estado = 1;
            }
            $inscripcion->save();
        }, 'No se pudo cambiar el estado de la inscripción.');
        if (!$guardado) {
            return;
        }
        $this->alertaInscripcion('¡Exito!', 'El estado de la inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido actualizado satisfactoriamente', 'success', 'Aceptar', 'success');
    }

    //Cargamos los datos de la inscripción para mostrarlos en el modal
    public function cargarInscripcion(Inscripcion $inscripcion, $value)
    {
        // el programa solo se edita en inscripciones del proceso activo, las anteriores se trasladan
        if ($inscripcion->programa_proceso->id_admision != getAdmision()->id_admision) {
            $this->dispatchBrowserEvent('modal', [
                'titleModal' => '#ModalInscripcionEditar',
            ]);
            $this->alertaInscripcion('¡Información!', 'Solo se puede editar el programa de las inscripciones del proceso de admisión activo.', 'info', 'Aceptar', 'info');
            return;
        }
        $this->resetErrorBag();
        $this->id_inscripcion = $inscripcion->id_inscripcion;
        $this->modalidad = $inscripcion->programa_proceso->programa_plan->programa->modalidad->id_modalidad;
        $this->programa = $inscripcion->programa_proceso->programa_plan->programa->id_programa;
        $this->programasModal = $this->programasProcesoActivo($this->modalidad);
    }

    //Actualizar el programa de la inscripción
    public function actualizarInscripcion()
    {
        //Validar que los campos no esten vacios
        $this->validate([
            'id_inscripcion' => 'required',
            'modalidad' => 'required',
            'programa' => 'required',
        ]);

        $inscripcion = Inscripcion::find($this->id_inscripcion);

        $programa_proceso_actualizado = Programa::join('programa_plan', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('programa_proceso', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->where('programa.id_modalidad', $this->modalidad)
            ->where('programa.id_programa', $this->programa)
            ->where('programa_proceso.id_admision', getAdmision()->id_admision)
            ->first();
        if (!$programa_proceso_actualizado) {
            $this->alertaInscripcion('¡Error!', 'El programa seleccionado no está disponible en el proceso de admisión activo.', 'error', 'Aceptar', 'danger');
            return;
        }
        //Validar que no hayan cambios
        if ($inscripcion->id_programa_proceso == $programa_proceso_actualizado->id_programa_proceso) {
            $this->alertaInscripcion('¡Información!', 'No se han realizado cambios en el programa de la inscripción', 'info', 'Aceptar', 'info');
            //Cerramos el modal
            $this->dispatchBrowserEvent('modal', [
                'titleModal' => '#ModalInscripcionEditar',
            ]);
            return;
        }

        $guardado = $this->ejecutar_transaccion(function () use ($inscripcion, $programa_proceso_actualizado) {
            $inscripcion->id_programa_proceso = $programa_proceso_actualizado->id_programa_proceso;
            $inscripcion->inscripcion_tipo_programa = $programa_proceso_actualizado->programa_tipo;
            $inscripcion->save();
        }, 'No se pudo actualizar el programa de la inscripción.');
        if (!$guardado) {
            return;
        }
        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#ModalInscripcionEditar',
        ]);
        // actualizamos la ficha de inscripcion
        $this->actualizar_ficha_inscripcion($inscripcion, 'El programa de la inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido actualizado satisfactoriamente');
    }

    public function actualizar_ficha_inscripcion(Inscripcion $inscripcion, $mensaje = null)
    {
        try {
            generarFichaInscripcion($inscripcion->id_inscripcion);
        } catch (\Throwable $e) {
            report($e);
            $this->alertaInscripcion(
                $mensaje ? '¡Atención!' : '¡Error!',
                ($mensaje ? $mensaje . ', pero no' : 'No') . ' se pudo generar la ficha de inscripción. Intente con la opción "Actualizar Ficha de Inscripción".',
                $mensaje ? 'warning' : 'error',
                'Aceptar',
                $mensaje ? 'warning' : 'danger'
            );
            return;
        }

        $mensaje = $mensaje ?? 'La ficha de inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido actualizada satisfactoriamente';
        try {
            ProcessRegistroFichaInscripcion2::dispatch($inscripcion, 'update');
            $this->alertaInscripcion('¡Exito!', $mensaje . ' y enviada a su correo.', 'success', 'Aceptar', 'success');
        } catch (\Throwable $e) {
            report($e);
            $this->alertaInscripcion('¡Atención!', $mensaje . ', pero no se pudo enviar la ficha a su correo.', 'warning', 'Aceptar', 'warning');
        }
    }

    public function cargar_expedientes($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        $programa = $inscripcion->programa_proceso->programa_plan->programa;
        $this->id_inscripcion = $inscripcion->id_inscripcion;
        $this->expediente_postulante = $inscripcion->persona->nombre_completo . ' - ' . $inscripcion->persona->numero_documento . ' - ' . $inscripcion->inscripcion_codigo;
        $this->expediente_programa = $programa->programa . ' EN ' . $programa->subprograma . ($programa->mencion ? ' CON MENCION EN ' . $programa->mencion : '') . ' - ' . formatearAdmisionVisual($inscripcion->programa_proceso->admision->admision);
        $this->expedientes = ExpedienteInscripcion::query()
            ->where('id_inscripcion', $id_inscripcion)
            ->whereHas('expediente_admision', function ($query) use ($inscripcion) {
                $query->where('id_admision', $inscripcion->programa_proceso->id_admision);
            })
            ->get();
    }

    //Actualizamos el estado de la inscripcion segun la verificacion de sus expedientes (solo BD, se llama dentro de la transaccion)
    //Retorna true si la inscripcion quedo verificada
    private function actualizar_verificacion_inscripcion($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        $expedientes = ExpedienteInscripcion::query()
            ->where('id_inscripcion', $id_inscripcion)
            ->whereHas('expediente_admision', function ($query) use ($inscripcion) {
                $query->where('id_admision', $inscripcion->programa_proceso->id_admision);
            })
            ->get();
        $cantidad = $expedientes->count();
        $verificados = $expedientes->where('expediente_inscripcion_verificacion', 1)->count();
        if ($cantidad == $verificados) {
            $inscripcion->inscripcion_estado = 1; //verificado
            $inscripcion->verificar_expedientes = 1; //verificado
            $inscripcion->save();
            return true;
        }

        $inscripcion->inscripcion_estado = 0; //pendiente
        // si queda algun expediente rechazado la inscripcion sigue observada
        $inscripcion->verificar_expedientes = $expedientes->where('expediente_inscripcion_verificacion', 2)->count() > 0 ? 2 : 0;
        $inscripcion->save();
        return false;
    }

    //Notificamos al postulante que su inscripcion fue verificada (despues de guardar los cambios)
    private function notificar_inscripcion_verificada($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        if ($this->enviar_correo_inscripcion($inscripcion->id_inscripcion, 'verificar-inscripcion')) {
            $this->alertaInscripcion('¡Exito!', 'La inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido verificada y se le notificó por correo.', 'success', 'Aceptar', 'success');
        } else {
            $this->alertaInscripcion('¡Atención!', 'La inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido verificada, pero no se pudo enviar el correo de notificación.', 'warning', 'Aceptar', 'warning');
        }
    }

    public function verificar_expediente($id_expediente_inscripcion)
    {
        $expediente = ExpedienteInscripcion::find($id_expediente_inscripcion);
        $inscripcion_verificada = false;
        $guardado = $this->ejecutar_transaccion(function () use ($expediente, &$inscripcion_verificada) {
            $expediente->expediente_inscripcion_verificacion = 1; //verificado
            $expediente->save();
            // verificar si todos los expedientes estan verificados para verificar la inscripcion
            $inscripcion_verificada = $this->actualizar_verificacion_inscripcion($expediente->id_inscripcion);
        }, 'No se pudo verificar el expediente.');

        if ($guardado) {
            $this->toastInscripcion('success', 'Expediente verificado: ' . $expediente->expediente_admision->expediente->expediente);
            if ($inscripcion_verificada) {
                $this->notificar_inscripcion_verificada($expediente->id_inscripcion);
            }
        }
        // cargar expedientes
        $this->cargar_expedientes($expediente->id_inscripcion);
    }

    //Verificamos de una vez todos los expedientes pendientes (los rechazados no se tocan)
    public function verificar_expedientes_pendientes()
    {
        $inscripcion = Inscripcion::find($this->id_inscripcion);
        $cantidad = 0;
        $inscripcion_verificada = false;
        $guardado = $this->ejecutar_transaccion(function () use ($inscripcion, &$cantidad, &$inscripcion_verificada) {
            $pendientes = ExpedienteInscripcion::query()
                ->where('id_inscripcion', $inscripcion->id_inscripcion)
                ->whereHas('expediente_admision', function ($query) use ($inscripcion) {
                    $query->where('id_admision', $inscripcion->programa_proceso->id_admision);
                })
                ->where('expediente_inscripcion_verificacion', 0)
                ->get();
            foreach ($pendientes as $expediente) {
                $expediente->expediente_inscripcion_verificacion = 1; //verificado
                $expediente->save();
            }
            $cantidad = $pendientes->count();
            $inscripcion_verificada = $this->actualizar_verificacion_inscripcion($inscripcion->id_inscripcion);
        }, 'No se pudieron verificar los expedientes.');

        if ($guardado) {
            $this->toastInscripcion('success', $cantidad . ' expediente(s) verificado(s)');
            if ($inscripcion_verificada) {
                $this->notificar_inscripcion_verificada($inscripcion->id_inscripcion);
            }
        }
        $this->cargar_expedientes($inscripcion->id_inscripcion);
    }

    public function rechazar_expediente($id_expediente_inscripcion)
    {
        $expediente = ExpedienteInscripcion::find($id_expediente_inscripcion);
        $guardado = $this->ejecutar_transaccion(function () use ($expediente) {
            $expediente->expediente_inscripcion_verificacion = 2; //rechazado
            $expediente->save();

            // cambiar el estado de la verificacion de expedientes de la inscripcion a observado
            $inscripcion = Inscripcion::find($expediente->id_inscripcion);
            $inscripcion->verificar_expedientes = 2; //observado
            $inscripcion->save();
        }, 'No se pudo rechazar el expediente.');
        if (!$guardado) {
            $this->cargar_expedientes($expediente->id_inscripcion);
            return;
        }

        // enviamos el correo de rechazo de expediente
        if ($this->enviar_correo_inscripcion($expediente->id_inscripcion, 'observar-expediente')) {
            $this->toastInscripcion('success', 'Expediente rechazado y notificado al postulante: ' . $expediente->expediente_admision->expediente->expediente);
        } else {
            $this->toastInscripcion('warning', 'Expediente rechazado, pero no se pudo enviar el correo al postulante');
        }
        // cargar expedientes
        $this->cargar_expedientes($expediente->id_inscripcion);
    }

    public function cargar_inscripcion($id_inscripcion)
    {
        $this->resetErrorBag();
        $this->id_inscripcion = $id_inscripcion;
        $inscripcion = Inscripcion::find($id_inscripcion);
        $this->estado = $inscripcion->inscripcion_estado;
        $this->observacion_inscripcion = $inscripcion->inscripcion_observacion;
    }

    public function editar_estado()
    {
        // validar que el estado sea observado y tenga observacion
        if ($this->estado == 2 && $this->observacion_inscripcion == null) {
            $this->validate([
                'observacion_inscripcion' => 'required',
            ]);
        }
        // actualizar estado
        $inscripcion = Inscripcion::find($this->id_inscripcion);
        $guardado = $this->ejecutar_transaccion(function () use ($inscripcion) {
            $inscripcion->inscripcion_estado = $this->estado;
            if ($inscripcion->inscripcion_estado == 2) {
                $inscripcion->inscripcion_observacion = $this->observacion_inscripcion;
            } else {
                $inscripcion->inscripcion_observacion = null;
            }
            $inscripcion->save();
        }, 'No se pudo actualizar el estado de la inscripción.');
        if (!$guardado) {
            return;
        }
        // cerrar modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modal-estado-inscripcion',
        ]);
        // ejecutamos el job para enviar el correo de observacion o verificacion de expediente
        $correo_enviado = true;
        if ($inscripcion->inscripcion_estado == 2) {
            $correo_enviado = $this->enviar_correo_inscripcion($inscripcion->id_inscripcion, 'observar-inscripcion');
        } else if ($inscripcion->inscripcion_estado == 1) {
            $correo_enviado = $this->enviar_correo_inscripcion($inscripcion->id_inscripcion, 'verificar-inscripcion');
        }
        // mostrar alerta
        $mensaje = 'El estado de la inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido actualizado satisfactoriamente';
        if ($correo_enviado) {
            $this->alertaInscripcion('¡Exito!', $mensaje, 'success', 'Aceptar', 'success');
        } else {
            $this->alertaInscripcion('¡Atención!', $mensaje . ', pero no se pudo enviar el correo al postulante.', 'warning', 'Aceptar', 'warning');
        }
        // limpiamos variables
        $this->reset('id_inscripcion', 'estado', 'observacion_inscripcion');
    }

    public function eliminar_inscripcion($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea eliminar la inscripción de ' . $inscripcion->persona->nombre_completo . '? Se eliminarán también su pago, sus expedientes y su usuario.', 'warning', 'Eliminar', 'Cancelar', 'danger', 'light', 'eliminar', $inscripcion->id_inscripcion);
    }

    public function eliminar($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        $archivos = []; // los archivos se eliminan despues de confirmar la transaccion

        $guardado = $this->ejecutar_transaccion(function () use ($inscripcion, $id_inscripcion, &$archivos) {
            // verificamos si tiene expedientes y si tiene lo eliminamos
            $expedientes = ExpedienteInscripcion::where('id_inscripcion', $id_inscripcion)->get();
            foreach ($expedientes as $expediente) {
                // verificamos si la inscripcion tiene expedientes en seguimiento y los eliminamos
                $expedientes_seguimiento = ExpedienteInscripcionSeguimiento::where('id_expediente_inscripcion', $expediente->id_expediente_inscripcion)->get();
                foreach ($expedientes_seguimiento as $expediente_seguimiento) {
                    $expediente_seguimiento->delete();
                }

                // el archivo solo se elimina si ninguna otra inscripcion lo usa (ej. una inscripcion trasladada)
                $archivo_compartido = ExpedienteInscripcion::where('expediente_inscripcion_url', $expediente->expediente_inscripcion_url)
                    ->where('id_inscripcion', '!=', $id_inscripcion)
                    ->exists();
                if (!$archivo_compartido) {
                    $archivos[] = $expediente->expediente_inscripcion_url;
                }
                // eliminamos el expediente
                $expediente->delete();
            }

            // el pago solo se elimina si ninguna otra inscripcion lo usa (ej. una inscripcion trasladada)
            $pago_compartido = Inscripcion::where('id_pago', $inscripcion->id_pago)
                ->where('id_inscripcion', '!=', $id_inscripcion)
                ->exists();
            if ($inscripcion->pago && !$pago_compartido) {
                $archivos[] = $inscripcion->pago->pago_voucher_url;
                $inscripcion->pago->delete();
            }

            // el usuario del postulante solo se elimina si no tiene otras inscripciones
            $persona = $inscripcion->persona;
            $otras_inscripciones = Inscripcion::where('id_persona', $persona->id_persona)
                ->where('id_inscripcion', '!=', $id_inscripcion)
                ->exists();
            $usuario_estudiante = UsuarioEstudiante::where('id_persona', $persona->id_persona)->first();
            if ($usuario_estudiante && !$otras_inscripciones) {
                $usuario_estudiante->delete();
            }

            // si es una inscripcion trasladada, la inscripcion original deja de estar reservada (se deshace el traslado)
            if ($inscripcion->id_inscripcion_origen) {
                Inscripcion::where('id_inscripcion', $inscripcion->id_inscripcion_origen)->update(['retiro_inscripcion' => 0]);
            }

            // eliminamos la inscripcion
            $inscripcion->delete();
        }, 'No se pudo eliminar la inscripción.');
        if (!$guardado) {
            return;
        }

        // eliminamos los files si existen en el proyecto
        foreach ($archivos as $archivo) {
            if ($archivo && file_exists($archivo)) {
                unlink($archivo);
            }
        }

        // mostrar alerta
        $this->alertaInscripcion(
            '¡Exito!',
            'La inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido eliminada satisfactoriamente',
            'success',
            'Aceptar',
            'success'
        );
    }

    //Pedimos confirmacion antes de reservar la inscripcion
    public function confirmar_reserva($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea reservar la inscripción de ' . $inscripcion->persona->nombre_completo . '? Dejará de considerarse en las listas y evaluaciones de su proceso.', 'question', 'Reservar', 'Cancelar', 'primary', 'light', 'reservar_inscripcion', $inscripcion->id_inscripcion);
    }

    public function reservar_inscripcion($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        $guardado = $this->ejecutar_transaccion(function () use ($inscripcion) {
            $inscripcion->retiro_inscripcion = 1;
            $inscripcion->save();
        }, 'No se pudo reservar la inscripción.');
        if (!$guardado) {
            return;
        }

        // mostrar alerta
        $this->alertaInscripcion(
            '¡Exito!',
            'La inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido reservada satisfactoriamente',
            'success',
            'Aceptar',
            'success'
        );
    }

    //Cargamos los datos de la inscripción y los programas del proceso activo para el modal de traslado
    public function cargar_traslado($id_inscripcion)
    {
        $this->limpiar_traslado();
        $inscripcion = Inscripcion::find($id_inscripcion);

        $error = validarTrasladoInscripcion($inscripcion);
        if ($error) {
            $this->dispatchBrowserEvent('modal', [
                'titleModal' => '#modal-traslado-inscripcion',
            ]);
            $this->alertaInscripcion('¡Información!', $error, 'info', 'Aceptar', 'info');
            return;
        }

        $programa = $inscripcion->programa_proceso->programa_plan->programa;
        $this->id_inscripcion = $inscripcion->id_inscripcion;
        $this->traslado_postulante = $inscripcion->persona->nombre_completo . ' - ' . $inscripcion->persona->numero_documento;
        $this->traslado_inscripcion = $inscripcion->inscripcion_codigo . ' - ' . formatearAdmisionVisual($inscripcion->programa_proceso->admision->admision);
        $this->traslado_programa = $programa->modalidad->modalidad . ' - ' . $programa->programa . ' EN ' . $programa->subprograma . ($programa->mencion ? ' CON MENCION EN ' . $programa->mencion : '');

        $this->programasTraslado = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
            ->where('programa_proceso.id_admision', getAdmision()->id_admision)
            ->where('programa_proceso.programa_proceso_estado', 1)
            ->where('programa_plan.programa_plan_estado', 1)
            ->select('programa_proceso.id_programa_proceso', 'programa.programa_iniciales', 'programa.programa', 'programa.subprograma', 'programa.mencion', 'programa.programa_tipo', 'modalidad.modalidad')
            ->orderBy('programa.programa_tipo')
            ->orderBy('programa.programa')
            ->orderBy('programa.subprograma')
            ->get()
            ->toArray();

        // seleccionamos por defecto el mismo programa en el proceso activo
        $equivalente = collect($this->programasTraslado)->first(function ($item) use ($programa) {
            return $item['programa_iniciales'] == $programa->programa_iniciales
                && $item['programa_tipo'] == $programa->programa_tipo
                && $item['programa'] == $programa->programa
                && $item['subprograma'] == $programa->subprograma
                && $item['mencion'] == $programa->mencion;
        });
        $this->programa_proceso_traslado = $equivalente ? $equivalente['id_programa_proceso'] : null;
    }

    //Trasladar la inscripción al proceso activo
    public function trasladar_inscripcion()
    {
        $this->validate([
            'id_inscripcion' => 'required',
            'programa_proceso_traslado' => 'required',
        ]);

        $inscripcion = Inscripcion::find($this->id_inscripcion);
        $error = validarTrasladoInscripcion($inscripcion, $this->programa_proceso_traslado);
        if ($error) {
            $this->alertaInscripcion('¡Error!', $error, 'error', 'Aceptar', 'danger');
            return;
        }

        // el traslado se hace en una transaccion: si falla no se guarda ningun cambio
        try {
            $nueva_inscripcion = trasladarInscripcion($inscripcion, $this->programa_proceso_traslado);
        } catch (\RuntimeException $e) {
            $this->alertaInscripcion('¡Error!', $e->getMessage(), 'error', 'Aceptar', 'danger');
            return;
        } catch (\Throwable $e) {
            report($e);
            $this->alertaInscripcion('¡Error!', 'No se pudo trasladar la inscripción. No se guardó ningún cambio. Intente nuevamente y, si el problema continúa, comuníquese con soporte.', 'error', 'Aceptar', 'danger');
            return;
        }

        // enviamos la ficha de inscripcion del proceso activo al correo del postulante
        // el traslado ya quedo registrado, si falla el correo no se revierte
        $correo_enviado = true;
        try {
            ProcessRegistroFichaInscripcion2::dispatch($nueva_inscripcion, 'create');
        } catch (\Throwable $e) {
            report($e);
            $correo_enviado = false;
        }

        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modal-traslado-inscripcion',
        ]);
        $mensaje = 'La inscripción de ' . $nueva_inscripcion->persona->nombre_completo . ' ha sido trasladada al proceso actual con el código ' . $nueva_inscripcion->inscripcion_codigo;
        if ($correo_enviado) {
            $this->alertaInscripcion('¡Exito!', $mensaje, 'success', 'Aceptar', 'success');
        } else {
            $this->alertaInscripcion(
                '¡Atención!',
                $mensaje . ', pero no se pudo enviar la ficha a su correo. Puede reenviarla con la opción "Actualizar Ficha de Inscripción".',
                'warning',
                'Aceptar',
                'warning'
            );
        }
        $this->limpiar_traslado();
    }

    public function limpiar_traslado()
    {
        $this->reset(
            'id_inscripcion',
            'programa_proceso_traslado',
            'programasTraslado',
            'traslado_postulante',
            'traslado_inscripcion',
            'traslado_programa',
        );
        $this->resetErrorBag();
    }

    //Consulta de inscripciones con los filtros aplicados (se usa en la tabla y en la exportacion)
    private function consulta_inscripciones()
    {
        if ($this->seguimientoFiltro) { //Si existe el seguimientoFiltro, se cambia de consulta, con el fin de mostrar las inscripciones que tienen un seguimiento
            $query = ExpedienteInscripcionSeguimiento::Join('expediente_inscripcion', 'expediente_inscripcion_seguimiento.id_expediente_inscripcion', '=', 'expediente_inscripcion.id_expediente_inscripcion')
                ->Join('inscripcion', 'expediente_inscripcion.id_inscripcion', '=', 'inscripcion.id_inscripcion')
                ->whereNull('inscripcion.deleted_at')
                ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', $this->seguimientoFiltro);
        } else { //Si no existe el seguimientoFiltro, se muestra la consulta normal
            $query = Inscripcion::query();
        }

        return $query->Join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
            ->Join('admision', 'programa_proceso.id_admision', '=', 'admision.id_admision')
            ->Join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
            ->Join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
            ->Join('modalidad', 'programa.id_modalidad', '=', 'modalidad.id_modalidad')
            ->Join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
            ->when($this->search != '', function ($query) {
                $query->where(function ($query) {
                    $query->where('programa.programa', 'like', '%' . $this->search . '%')
                        ->orWhere('programa.subprograma', 'like', '%' . $this->search . '%')
                        ->orWhere('programa.mencion', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.apellido_paterno', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.apellido_materno', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.nombre_completo', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%')
                        ->orWhere('inscripcion.inscripcion_codigo', 'like', '%' . $this->search . '%')
                        ->orWhere('modalidad.modalidad', 'like', '%' . $this->search . '%');
                });
            })
            ->when(filled($this->modalidadFiltro), function ($query) {
                $query->where('programa.id_modalidad', $this->modalidadFiltro);
            })
            ->when(filled($this->programaFiltro), function ($query) {
                $query->where('programa_plan.id_programa', $this->programaFiltro);
            })
            ->when(filled($this->procesoFiltro), function ($query) {
                $query->where('programa_proceso.id_admision', $this->procesoFiltro);
            })
            ->when(filled($this->estadoFiltro), function ($query) {
                $query->where('inscripcion.inscripcion_estado', $this->estadoFiltro);
            })
            ->when(filled($this->estado_expediente_filtro) && $this->estado_expediente_filtro != 'all', function ($query) {
                $query->where('inscripcion.verificar_expedientes', $this->estado_expediente_filtro);
            })
            ->when($this->mesFiltro, function ($query, $mesFiltro) {
                return $query->whereMonth('inscripcion.inscripcion_fecha', $mesFiltro);
            })
            ->orderBy('inscripcion.id_inscripcion', 'desc');
    }

    //Exportamos a Excel las inscripciones con los filtros aplicados
    public function excel()
    {
        $admision = $this->procesoFiltro ? Admision::find($this->procesoFiltro) : null;
        $nombre = 'inscripciones-' . ($admision ? Str::slug(formatearAdmisionVisual($admision->admision)) : 'todos-los-procesos') . '-' . date('Ymd-His') . '.xlsx';

        return Excel::download(new listaInscripcionesExport($this->consulta_inscripciones()->get()), $nombre);
    }

    public function render()
    {
        $inscripcionModel = $this->consulta_inscripciones()->paginate($this->cant_paginas);

        //Procesos de admision del mas reciente al mas antiguo
        $procesos = Admision::orderBy('admision_año', 'desc')
            ->orderBy('admision_convocatoria', 'desc')
            ->get();
        $admisionActiva = $procesos->firstWhere('admision_estado', 1);

        //Programas para el filtro: los del proceso y modalidad seleccionados
        $programasFiltro = Programa::join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
            ->when($this->proceso_filtro, function ($query) {
                $query->whereIn('programa.id_programa', ProgramaPlan::join('programa_proceso', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                    ->where('programa_proceso.id_admision', $this->proceso_filtro)
                    ->select('programa_plan.id_programa'));
            })
            ->when($this->modalidad_filtro, function ($query) {
                $query->where('programa.id_modalidad', $this->modalidad_filtro);
            })
            ->select('programa.*', 'modalidad.modalidad')
            ->orderBy('programa.programa_tipo')
            ->orderBy('programa.programa')
            ->orderBy('programa.subprograma')
            ->orderBy('modalidad.modalidad')
            ->get();

        //Meses en los que hubo inscripciones en el proceso seleccionado
        $mesesFiltro = $this->proceso_filtro
            ? Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->where('programa_proceso.id_admision', $this->proceso_filtro)
                ->selectRaw('MONTH(inscripcion.inscripcion_fecha) as mes, YEAR(inscripcion.inscripcion_fecha) as anio')
                ->groupBy('mes', 'anio')
                ->orderBy('anio')
                ->orderBy('mes')
                ->get()
            : collect();

        //Resumen del proceso seleccionado
        $resumen = $this->procesoFiltro
            ? Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->where('programa_proceso.id_admision', $this->procesoFiltro)
                ->selectRaw('SUM(COALESCE(inscripcion.retiro_inscripcion, 0) = 0) as inscritos')
                ->selectRaw('SUM(COALESCE(inscripcion.retiro_inscripcion, 0) = 0 AND inscripcion.inscripcion_estado = 1) as verificados')
                ->selectRaw('SUM(COALESCE(inscripcion.retiro_inscripcion, 0) = 0 AND inscripcion.inscripcion_estado = 0) as pendientes')
                ->selectRaw('SUM(COALESCE(inscripcion.retiro_inscripcion, 0) = 0 AND inscripcion.inscripcion_estado = 2) as observados')
                ->selectRaw('SUM(COALESCE(inscripcion.retiro_inscripcion, 0) = 0 AND inscripcion.verificar_expedientes = 2) as expedientes_observados')
                ->selectRaw('SUM(inscripcion.retiro_inscripcion = 1) as reservados')
                ->first()
            : null;

        //Modalidades con programas en el proceso activo (para el modal de editar programa)
        $modalidadesModal = Modalidad::whereIn('id_modalidad', $this->programasProcesoActivo()->pluck('id_modalidad'))->get();

        //Etiquetas de los filtros aplicados
        $modalidades = Modalidad::all();
        $seguimientos = TipoSeguimiento::all();
        $estados = ['0' => 'Pendiente', '1' => 'Verificado', '2' => 'Observado'];
        $filtrosAplicados = [];
        if (filled($this->procesoFiltro)) {
            $proceso = $procesos->firstWhere('id_admision', $this->procesoFiltro);
            $filtrosAplicados['proceso'] = 'Proceso: ' . ($proceso ? formatearAdmisionVisual($proceso->admision) : $this->procesoFiltro);
        }
        if (filled($this->modalidadFiltro)) {
            $filtrosAplicados['modalidad'] = 'Modalidad: ' . optional($modalidades->firstWhere('id_modalidad', $this->modalidadFiltro))->modalidad;
        }
        if (filled($this->programaFiltro)) {
            $programa = Programa::find($this->programaFiltro);
            $filtrosAplicados['programa'] = 'Programa: ' . ($programa ? $programa->programa . ' EN ' . $programa->subprograma . ($programa->mencion ? ' CON MENCION EN ' . $programa->mencion : '') : $this->programaFiltro);
        }
        if (filled($this->seguimientoFiltro)) {
            $filtrosAplicados['seguimiento'] = 'Seguimiento: ' . optional($seguimientos->firstWhere('id_tipo_seguimiento', $this->seguimientoFiltro))->tipo_seguimiento;
        }
        if (filled($this->mesFiltro)) {
            $filtrosAplicados['mes'] = 'Mes: ' . ($this->meses[$this->mesFiltro] ?? $this->mesFiltro);
        }
        if (filled($this->estadoFiltro)) {
            $filtrosAplicados['estado'] = 'Inscripción: ' . ($estados[$this->estadoFiltro] ?? $this->estadoFiltro);
        }
        if (filled($this->estado_expediente_filtro) && $this->estado_expediente_filtro != 'all') {
            $filtrosAplicados['estado_expediente'] = 'Expedientes: ' . ($estados[$this->estado_expediente_filtro] ?? $this->estado_expediente_filtro);
        }
        if ($this->search != '') {
            $filtrosAplicados['search'] = 'Búsqueda: "' . $this->search . '"';
        }

        return view('livewire.modulo-administrador.gestion-admision.inscripcion.index', [
            'inscripcionModel' => $inscripcionModel,
            'procesos' => $procesos,
            'seguimientos' => $seguimientos,
            'modalidades' => $modalidades,
            'filtrosAplicados' => $filtrosAplicados,
            'programasFiltro' => $programasFiltro,
            'mesesFiltro' => $mesesFiltro,
            'resumen' => $resumen,
            'modalidadesModal' => $modalidadesModal,
            'admisionActiva' => $admisionActiva,
            // solo se trasladan las inscripciones de programas sin admitidos (no aperturados)
            'programasConAdmitidos' => Admitido::distinct()->pluck('id_programa_proceso')->toArray(),
            'inscripcionesTrasladadas' => Inscripcion::whereNotNull('id_inscripcion_origen')->pluck('id_inscripcion_origen')->toArray(),
        ]);
    }
}
