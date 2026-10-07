<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\Admitidos;

use App\Models\Admision;
use App\Models\Admitido;
use App\Models\AdmitidoCiclo;
use App\Models\Evaluacion;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap

    public $admisiones, $admision, $id_admision, $filtro_proceso, $proceso; // Variables para la tabla de admisiones
    public $admitidos; // Variables para la tabla de admitidos

    public $search; // Variable para la busqueda

    protected $queryString = [
        'search' => ['except' => ''],
    ]; // Variable para la busqueda

    protected $listeners = [
        'generar_codigo' => 'generar_codigo',
    ];

    public function mount()
    {
        $this->admisiones = Admision::all(); // obtengo todas las admisiones
        $admision = Admision::where('admision_estado', '1')->first(); // obtengo la admision activa
        $this->filtro_proceso = $admision->id_admision; // obtengo el id de la admision activa
        $this->proceso = $this->filtro_proceso; // obtengo el id de la admision activa
    }

    public function aplicar_filtro()
    {
        if($this->filtro_proceso == null || $this->filtro_proceso == "")
        {
            $this->mount();
        }
        else
        {
            $this->proceso = $this->filtro_proceso;
        }
    }

    public function resetear_filtro()
    {
        $this->mount();
    }

    public function alerta_generar_codigo()
    {
        // validamos la fecha en la que se esta generando los codigos de los admitidos
        $admision = Admision::where('admision_estado', 1)->first();
        if($admision->admision_fecha_resultados != date('Y-m-d'))
        {
            $this->dispatchBrowserEvent('alerta_admitido', [
                'title' => '¡Atención!',
                'text' => 'No se puede generar a los admitidos, la fecha de generación es el '.date('d/m/Y', strtotime($admision->admision_fecha_resultados)).'.',
                'icon' => 'warning',
                'confirmButtonText' => 'Aceptar',
                'color' => 'warning',
            ]);
            return redirect()->back();
        }

        // validamos si hay evaluaciones con estado de admitido para generar los codigos
        $evaluacion = Evaluacion::join('inscripcion', 'evaluacion.id_inscripcion', '=', 'inscripcion.id_inscripcion')
                ->join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
                ->join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                ->where('evaluacion.evaluacion_estado', 2)
                ->where('evaluacion.evaluacion_estado_admitido', 0)
                ->where('programa_proceso.id_admision', $admision->id_admision)
                ->orderBy('programa.id_programa')
                ->orderBy('persona.nombre_completo')
                ->get()->count();
        if($evaluacion == 0)
        {
            $this->dispatchBrowserEvent('alerta_admitido', [
                'title' => '¡Atención!',
                'text' => 'No hay evaluaciones con estado de admitido para generar los codigos.',
                'icon' => 'warning',
                'confirmButtonText' => 'Aceptar',
                'color' => 'warning',
            ]);
            return redirect()->back();
        }

        // mostramos la alerta de confirmacion para generar los codigos de los admitidos
        $this->dispatchBrowserEvent('alerta_generar_codigo', [
            'title' => '¡Atención!',
            'text' => '¿Estas seguro de generar los codigos de los admitidos?',
            'icon' => 'question',
            'confirmButtonText' => 'Si, generar',
            'cancelButtonText' => 'No, cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger',
        ]);
    }

    public function generar_codigo()
    {
        $admision = Admision::where('admision_estado', 1)->first();
        $evaluacion = Evaluacion::join('inscripcion', 'evaluacion.id_inscripcion', '=', 'inscripcion.id_inscripcion')
                ->join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
                ->join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                ->where('evaluacion.evaluacion_estado', 2)
                ->where('evaluacion.evaluacion_estado_admitido', 0)
                ->where('programa_proceso.id_admision', $admision->id_admision)
                ->orderBy('programa.id_programa')
                ->orderBy('persona.nombre_completo')
                ->get();
        $admision_año = $admision->admision_año; // obtengo el año de la admision activa
        $admision_año = substr($admision_año, 2, 2); // obtengo los ultimos 2 digitos del año de la admision activa
        $codigo_doctorado = '0D0'; // codigo de doctorado inicial
        $codigo_maestria = '0M0'; // codigo de maestria inicial

        foreach($evaluacion as $item){ // recorremos los admitidos
            // generamos codigo de doctorado
            if ($item->programa_tipo == 2) {
                // 1) Determinamos la letra según la modalidad
                $letter = $item->id_modalidad == 1 ? 'P' : 'D';
                // 2) Construimos el prefijo fijo: ej. "0D0P25"
                $prefix = $codigo_doctorado . $letter . $admision_año;

                // 3) Buscamos el registro con el código máximo para ese prefijo
                $max = Admitido::where('admitido_codigo', 'like', $prefix . '%')
                               ->orderBy('admitido_codigo', 'desc')
                               ->first();

                // 4) Calculamos el siguiente número
                if ($max) {
                    // Extraemos los 4 dígitos justo después del prefijo
                    $lastNum = intval(substr($max->admitido_codigo, strlen($prefix), 4));
                    $nextNum = $lastNum + 1;
                } else {
                    // Si no hay ningún registro previo, empezamos por 1
                    $nextNum = 1;
                }

                // 5) Formateamos a 4 dígitos con ceros a la izquierda
                $counter = str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                // 6) Montamos el código final
                $codigo = $prefix . $counter;
            }


            // generamos codigo de maestria
            if ($item->programa_tipo == 1) {
                // 1) Determinamos la letra según la modalidad
                $letter = $item->id_modalidad == 1 ? 'P' : 'D';
                // 2) Construimos el prefijo fijo: ej. "0M0P25"
                $prefix = $codigo_maestria . $letter . $admision_año;

                // 3) Buscamos el registro con el código máximo para ese prefijo
                $max = Admitido::where('admitido_codigo', 'like', $prefix . '%')
                               ->orderBy('admitido_codigo', 'desc')
                               ->first();

                // 4) Calculamos el siguiente número
                if ($max) {
                    // Extraemos los 4 dígitos justo después del prefijo
                    $lastNum = intval(substr($max->admitido_codigo, strlen($prefix), 4));
                    $nextNum = $lastNum + 1;
                } else {
                    // Si no hay ningún registro previo, empezamos por 1
                    $nextNum = 1;
                }

                // 5) Formateamos a 4 dígitos con ceros a la izquierda
                $counter = str_pad($nextNum, 4, '0', STR_PAD_LEFT);

                // 6) Montamos el código final
                $codigo = $prefix . $counter;
            }

            // creamos al usuario admitido
            $admitido = new Admitido();
            $admitido->admitido_codigo = $codigo;
            $admitido->id_persona = $item->id_persona;
            $admitido->id_evaluacion = $item->id_evaluacion;
            $admitido->id_programa_proceso = $item->id_programa_proceso;
            $admitido->id_tipo_estudiante = 1;
            $admitido->admitido_estado = 1;
            $admitido->save();

            // creamos al usuario admitido en el ciclo 1
            // $admitido_ciclo = new AdmitidoCiclo();
            // $admitido_ciclo->id_admitido = $admitido->id_admitido;
            // $admitido_ciclo->id_ciclo = 1;
            // $admitido_ciclo->admitido_ciclo_estado = 1;
            // $admitido_ciclo->save();

            // actualizamos el estado de la evaluacion a admitido
            $evaluacion = Evaluacion::find($item->id_evaluacion);
            $evaluacion->evaluacion_estado_admitido = 1;
            $evaluacion->save();
        }

        // emitimos una alerta de exito que se admitieron los postulantes
        $this->dispatchBrowserEvent('alerta_admitido', [
            'title' => '¡Exito!',
            'text' => 'Se admitieron los postulantes correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success',
        ]);
    }

    public function render()
    {
        $this->admision = Admision::find($this->proceso); // obtengo la admision activa
        $this->admitidos = Admitido::join('persona', 'persona.id_persona', '=', 'admitido.id_persona')
                            ->join('evaluacion', 'evaluacion.id_evaluacion', '=', 'admitido.id_evaluacion')
                            ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'admitido.id_programa_proceso')
                            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                            ->where('programa_proceso.id_admision', $this->proceso)
                            ->where(function ($query) {
                                $query->where('persona.nombre_completo', 'like', '%' . $this->search . '%')
                                    ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%')
                                    ->orWhere('admitido.admitido_codigo', 'like', '%' . $this->search . '%');
                            })
                            ->orderBy('admitido.admitido_codigo', 'asc')
                            ->get();

        return view('livewire.modulo-administrador.gestion-admision.admitidos.index');
    }

}
