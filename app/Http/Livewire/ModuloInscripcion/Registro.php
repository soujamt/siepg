<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\Admision;
use App\Models\CanalPago;
use App\Models\ConceptoPago;
use App\Models\Discapacidad;
use App\Models\EstadoCivil;
use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\ExpedienteTipoSeguimiento;
use App\Models\Genero;
use App\Models\GradoAcademico;
use App\Models\Inscripcion;
use App\Models\Modalidad;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\ProgramaProceso;
use App\Models\TipoDocumento;
use App\Models\TipoSeguimiento;
use App\Models\Ubigeo;
use App\Models\Universidad;
use App\Models\UsuarioEstudiante;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class Registro extends Component
{
    use WithFileUploads; // sirve para subir archivos

    public $paso = 0; // variable para el paso de la vista
    public $total_pasos = 3; // variable para el total de pasos de la vista

    public $admision; // variable para el nombre de la admision

    public $tipo_documento, $documento_identidad, $numero_operacion, $monto_operacion, $fecha_pago, $canal_pago, $concepto_pago, $voucher; // variables para el formulario del modal de registro
    public $id_inscripcion; // variable para el id de la inscripcion
    public $id_persona, $documento, $paterno, $materno, $nombres, $fecha_nacimiento, $genero, $estado_civil, $grado_academico, $especialidad_carrera, $discapacidad, $direccion, $celular, $celular_opcional, $año_egreso, $email, $email_opcional, $universidad, $centro_trabajo; // variables para el formulario de registro de información personal
    public $programa_array, $programa; // variables para el formulario de registro de información de programa
    public $modalidad_array, $modalidad; // variables para el formulario de registro de información de modalidad
    public $ubigeo_direccion_array, $ubigeo_direccion, $pais_direccion; // variables para el formulario de registro de información de dirección
    public $ubigeo_nacimiento_array, $ubigeo_nacimiento, $pais_nacimiento; // variables para el formulario de registro de información de nacimiento

    public $expediente, $expediente_array, $expedientes_count, $expedientes = []; // variable para el formulario de registro de expediente
    public $mostrar_tipo_expediente; // sirve para mostrar los expedientes segun el programa que elija el usuario
    public $iteration; // sirve para actualizar el componente de expediente
    public $modo = 'create'; // sirve para cargar informacion del registro en los formularios
    public $modo_expediente = 'create'; // sirve para cargar informacion del registro en los formularios
    public $check_expediente = false;   // sirve para hacer el seguimiento de los expedientes de grado academico
    // true = acepta la declaracion jurada, false = no acepta la declaracion jurada
    public $declaracion_jurada = false; // sirve para aceptar la declaracion jurada al finalizar el registro de inscripcion y es obligatorio
    // true = acepta la declaracion jurada, false = no acepta la declaracion jurada

    public $check_formas_pago = false; // sirve para aceptar las formas de pago

    public function mount()
    {
        // verificar si el proceso de admision esta activo
        $proceso_aperturado = verificarProcesoAdmision();
        if ($proceso_aperturado == false) {
            abort(403, 'El proceso de admisión no se encuentra activo');
        }

        $this->paso = 1;
        $this->admision = Admision::where('admision_estado', 1)->first();
        // solo las modalidades que tienen programas ofertados en el proceso de admision activo
        $this->modalidad_array = Modalidad::where('modalidad_estado', 1)
            ->whereIn('id_modalidad', function ($query) {
                $query->select('programa.id_modalidad')
                    ->from('programa_proceso')
                    ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                    ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                    ->where('programa_proceso.id_admision', $this->admision->id_admision)
                    ->where('programa_proceso.programa_proceso_estado', 1)
                    ->where('programa_plan.programa_plan_estado', 1);
            })
            ->get();
        $this->programa_array = Collect();
        // si el proceso oferta una sola modalidad, se selecciona automaticamente
        if ($this->modalidad_array->count() == 1) {
            $this->modalidad = $this->modalidad_array->first()->id_modalidad;
            $this->updatedModalidad($this->modalidad);
        }
        $this->ubigeo_direccion_array = Ubigeo::all();
        $this->ubigeo_nacimiento_array = Ubigeo::all();
    }

    public function buscar_persona()
    {
        if ($this->documento_identidad == null) {
            // mostramos alerta de persona no encontrada
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'Debe ingresar un número de documento',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }
        $persona = Persona::where('numero_documento', $this->documento_identidad)->first();
        if ($persona) { // si existe la persona
            $this->id_persona = $persona->id_persona;
            $this->paterno = $persona->apellido_paterno;
            $this->materno = $persona->apellido_materno;
            $this->nombres = $persona->nombre;
            $this->fecha_nacimiento = $persona->fecha_nacimiento;
            $this->genero = $persona->id_genero;
            $this->estado_civil = $persona->id_estado_civil;
            $this->grado_academico = $persona->id_grado_academico;
            $this->especialidad_carrera = $persona->especialidad_carrera;
            $this->discapacidad = $persona->id_discapacidad;
            $this->direccion = $persona->direccion;
            $this->celular = $persona->celular;
            $this->celular_opcional = $persona->celular_opcional;
            $this->año_egreso = $persona->año_egreso;
            $this->email = $persona->correo;
            $this->email_opcional = $persona->correo_opcional;
            $this->universidad = $persona->id_universidad;
            $this->centro_trabajo = $persona->centro_trabajo;
            $this->pais_direccion = $persona->pais_direccion;
            $this->pais_nacimiento = $persona->pais_nacimiento;
            $this->ubigeo_direccion = $persona->ubigeo_direccion;
            $this->ubigeo_nacimiento = $persona->ubigeo_nacimiento;
            $this->modo = 'update';
            // mostramos alerta de persona no encontrada
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'Se encontró a la persona con el número de documento ' . $this->documento_identidad . ', continue con el registro de inscripción',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        } else {
            // mostramos alerta de persona no encontrada
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'No se encontró a la persona con el número de documento ingresado para rellenar los campos del formulario, continue con el registro de inscripción',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
    }

    public function updated($propertyName)
    {
        if ($this->paso == 1) {
            $this->validateOnly($propertyName, [
                'tipo_documento' => 'required|numeric',
                'documento_identidad' => 'required|digits_between:8,9',
                'numero_operacion' => 'required|max:50',
                'monto_operacion' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'canal_pago' => 'required|numeric',
                'voucher' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);
        } elseif ($this->paso == 2) {
            $this->validateOnly($propertyName, [
                'modalidad' => 'required|numeric',
                'programa' => 'required|numeric',
            ]);
        } elseif ($this->paso == 3) {
            $this->validateOnly($propertyName, [
                'paterno' => 'required|max:50',
                'materno' => 'required|max:50',
                'nombres' => 'required|max:50',
                'fecha_nacimiento' => 'required|date',
                'genero' => 'required|numeric',
                'estado_civil' => 'required|numeric',
                'grado_academico' => 'required|numeric',
                'especialidad_carrera' => 'required|max:50',
                'discapacidad' => 'required|numeric',
                'direccion' => 'required|max:100',
                'celular' => 'required|max:9',
                'celular_opcional' => 'nullable|max:9',
                'año_egreso' => 'required|numeric',
                'email' => 'required|email|max:50',
                'email_opcional' => 'nullable|email|max:50',
                'universidad' => 'required|numeric',
                'centro_trabajo' => 'required|max:50',
                'pais_direccion' => 'nullable|max:50',
                'ubigeo_direccion' => 'required|numeric',
                'pais_nacimiento' => 'nullable|max:50',
                'ubigeo_nacimiento' => 'required|numeric',
            ]);
        }
    }

    public function paso_1()
    {
        $this->paso = 1;
    }

    public function paso_2()
    {
        $this->validacion();

        $admision = Admision::where('admision_estado', 1)->first();

        // // si el pago tiene fecha de pago mayor al 20 de julio se valida para que ingrese un pago con fecha menor o igual al 20 de julio
        // $fecha_pago = $this->fecha_pago;
        // $fecha_pago = strtotime($fecha_pago);
        // $fecha_limite = '2024-07-20';
        // $fecha_limite = strtotime($fecha_limite);
        // if ($fecha_pago > $fecha_limite) {
        //     // emitir evento para mostrar mensaje de alerta
        //     $this->dispatchBrowserEvent('registro_inscripcion', [
        //         'title' => '¡Error!',
        //         'text' => 'La fecha de pago no puede ser mayor al 20 de julio',
        //         'icon' => 'error',
        //         'confirmButtonText' => 'Cerrar',
        //         'color' => 'danger'
        //     ]);
        //     return;
        // }

        // verificamos el el concepto de pago es de convenio o victimas de violencia
        if ($this->concepto_pago != getIdConceptoPagoConvenio() || $this->concepto_pago != getIdConceptoPagoVictimasViolencia()) {
            // validar si el numero de operacion ya existe
            $pago = Pago::where('pago_operacion', $this->numero_operacion)->first();
            if ($pago) {
                if ($pago->pago_documento == $this->documento_identidad && $pago->pago_fecha == $this->fecha_pago) {
                    // emitir evento para mostrar mensaje de alerta
                    $this->dispatchBrowserEvent('registro_inscripcion', [
                        'title' => '¡Error!',
                        'text' => 'El Número de Operación y el Documento de Identidad ya se encuentran registrados en el sistema en la fecha seleccionada',
                        'icon' => 'error',
                        'confirmButtonText' => 'Cerrar',
                        'color' => 'danger'
                    ]);
                    return;
                } else if ($pago->pago_fecha == $this->fecha_pago) {
                    // emitir evento para mostrar mensaje de alerta
                    $this->dispatchBrowserEvent('registro_inscripcion', [
                        'title' => '¡Error!',
                        'text' => 'El número de operación ya ha sido ingresado en la fecha seleccionada',
                        'icon' => 'error',
                        'confirmButtonText' => 'Cerrar',
                        'color' => 'danger'
                    ]);
                    return;
                } else if ($pago->pago_documento == $this->documento_identidad) {
                    // emitir evento para mostrar mensaje de alerta
                    $this->dispatchBrowserEvent('registro_inscripcion', [
                        'title' => '¡Error!',
                        'text' => 'El Número de Operación y el Documento de Identidad ya se encuentran registrados en el sistema',
                        'icon' => 'error',
                        'confirmButtonText' => 'Cerrar',
                        'color' => 'danger'
                    ]);
                    return;
                }
            }
        }

        // validar si el monto ingresado es igual al monto por concepto de inscripción
        $concepto_pago_monto = ConceptoPago::where('id_concepto_pago', $this->concepto_pago)->first()->concepto_pago_monto;
        if ($this->monto_operacion < $concepto_pago_monto) {
            // emitir evento para mostrar mensaje de alerta
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '¡Error!',
                'text' => 'El monto ingresado no es igual al monto por concepto de inscripción',
                'icon' => 'error',
                'confirmButtonText' => 'Cerrar',
                'color' => 'danger'
            ]);
            return;
        }
        $this->paso = 2;
    }

    public function paso_3()
    {
        $this->validacion();
        $this->paso = 3;
    }

    public function paso_4()
    {
        $this->validacion();
        $this->paso = 4;
    }

    public function paso_5()
    {
        $this->validacion();
        if (count($this->expedientes) < $this->expedientes_count) {
            // emitir evento para mostrar mensaje de alerta de expedientes incompletos
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'No se han subido todos los expedientes requeridos',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return; // redireccionar a la misma pagina
        }
        $this->paso = 5;
    }

    public function validacion()
    {
        if ($this->paso === 1) {
            $this->resetErrorBag();
            $this->resetValidation();
            $this->validate([
                'tipo_documento' => 'required|numeric',
                'documento_identidad' => 'required|digits_between:8,9',
                'numero_operacion' => 'required|max:50',
                'monto_operacion' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric',
                'voucher' => 'required|image|mimes:jpg,jpeg,png|max:4096'
            ]);
        } elseif ($this->paso === 2) {
            $this->resetErrorBag();
            $this->resetValidation();
            $this->validate([
                'modalidad' => 'required|numeric',
                'programa' => 'required|numeric',
            ]);
        } elseif ($this->paso === 3) {
            $this->resetErrorBag();
            $this->resetValidation();
            if ($this->ubigeo_direccion == 1893) {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'required|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'nullable|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            } else if ($this->ubigeo_nacimiento == 1893) {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'nullable|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'required|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            } elseif ($this->ubigeo_nacimiento == 1893 && $this->ubigeo_direccion == 1893) {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'required|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'required|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            } else {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'nullable|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'nullable|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            }
        } elseif ($this->paso === 4) {
            $this->resetErrorBag();
            $this->resetValidation();
            $this->validate([
                'expedientes' => 'required|array|min:1',
            ]);
        }
    }

    public function updatedModalidad($modalidad)
    {
        $this->programa_array = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('sede', 'sede.id_sede', '=', 'programa.id_sede')
            ->where('programa.id_modalidad', $modalidad)
            ->where('programa_proceso.id_admision', $this->admision->id_admision)
            ->where('programa_proceso.programa_proceso_estado', 1)
            ->where('programa_plan.programa_plan_estado', 1)
            ->get();
    }

    public function updatedPrograma($programa_proceso)
    {
        $programa = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('sede', 'sede.id_sede', '=', 'programa.id_sede')
            ->where('programa_proceso.id_programa_proceso', $programa_proceso)
            ->where('programa_proceso.id_admision', $this->admision->id_admision)
            ->first();
        // dd($this->programa_array);
        if ($programa) {
            $programa_tipo = $programa->programa_tipo;
            if ($programa_tipo == 1) {
                $this->mostrar_tipo_expediente = 1;
            } else if ($programa_tipo == 2) {
                $this->mostrar_tipo_expediente = 2;
            }
            $this->expediente_array = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', '=', 'expediente_admision.id_expediente')
                ->join('admision', 'admision.id_admision', '=', 'expediente_admision.id_admision')
                ->where('expediente_admision.expediente_admision_estado', 1)
                ->where('expediente.expediente_estado', 1)
                ->where('admision.admision_estado', 1)
                ->where(function ($query) {
                    $query->where('expediente.expediente_tipo', 0)
                        ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                })
                ->get();
            $this->expedientes_count = $this->expediente_array->count();
        } else {
            $this->expediente_array = null;
        }
    }

    public function updatedDeclaracionJurada($declaracion_jurada)
    {
        $this->validate([
            'declaracion_jurada' => 'accepted',
        ]);
    }

    public function registrar_inscripcion()
    {
        // dd($this->all());
        // validar declaracion jurada
        if ($this->declaracion_jurada == false) {
            // emitir evento para mostrar mensaje de alerta de declaracion jurada
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'Debe aceptar la declaración jurada para continuar con el proceso de inscripción',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);

            // validar el campo de declaracion jurada para que no se envie el formulario
            $this->validate([
                'declaracion_jurada' => 'accepted',
            ]);

            // redireccionar a la misma pagina
            return redirect()->back();
        }

        // verificamos el pais de procedencia de la direccion
        if ($this->ubigeo_direccion == 1893) {
            // Remove the unnecessary assignment to the same variable
            // $this->pais_direccion = $this->pais_direccion;
            $this->pais_direccion = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->pais_direccion);
        } else {
            $this->pais_direccion = 'PERU';
        }

        // verificamos el pais de procedencia de nacimiento
        if ($this->ubigeo_nacimiento == 1893) {
            // $this->pais_nacimiento = $this->pais_nacimiento;
            $this->pais_nacimiento = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->pais_nacimiento);
        } else {
            $this->pais_nacimiento = 'PERU';
        }

        // reemplazar tildes por letras sin tildes en los campos de apellido paterno, apellido materno y nombres
        $this->nombres = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->nombres);
        $this->paterno = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->paterno);
        $this->materno = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->materno);
        $this->direccion = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->direccion);
        $this->centro_trabajo = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->centro_trabajo);

        // registro de formulario de inscripcion
        $persona = Persona::where('numero_documento', $this->documento_identidad)->first();
        if ($persona) {
            // actualizar datos de persona
            $persona->apellido_paterno = mb_strtoupper($this->paterno, 'UTF-8');
            $persona->apellido_materno = mb_strtoupper($this->materno, 'UTF-8');
            $persona->nombre = mb_strtoupper($this->nombres, 'UTF-8');
            $persona->nombre_completo = mb_strtoupper($this->paterno . ' ' . $this->materno . ' ' . $this->nombres, 'UTF-8');
            $persona->id_genero = $this->genero;
            $persona->fecha_nacimiento = $this->fecha_nacimiento;
            $persona->direccion = mb_strtoupper($this->direccion, 'UTF-8');
            $persona->celular = $this->celular;
            if ($this->celular_opcional != null) {
                $persona->celular_opcional = $this->celular_opcional;
            } else {
                $persona->celular_opcional = null;
            }
            $persona->correo = mb_strtolower($this->email, 'UTF-8');
            if ($this->email_opcional != null) {
                $persona->correo_opcional = mb_strtolower($this->email_opcional, 'UTF-8');
            } else {
                $persona->correo_opcional = null;
            }
            $persona->año_egreso = $this->año_egreso;
            $persona->especialidad_carrera = mb_strtoupper($this->especialidad_carrera, 'UTF-8');
            $persona->centro_trabajo = mb_strtoupper($this->centro_trabajo, 'UTF-8');
            if (strlen($this->documento) == 8) {
                $persona->id_tipo_documento = 1;
            } else if (strlen($this->documento) == 9) {
                $persona->id_tipo_documento = 2;
            }
            $persona->id_discapacidad = $this->discapacidad;
            $persona->id_estado_civil = $this->estado_civil;
            $persona->id_grado_academico = $this->grado_academico;
            $persona->id_universidad = $this->universidad;
            $persona->ubigeo_direccion = $this->ubigeo_direccion;
            $persona->pais_direccion = $this->pais_direccion;
            $persona->ubigeo_nacimiento = $this->ubigeo_nacimiento;
            $persona->pais_nacimiento = $this->pais_nacimiento;
            $persona->save();

            // asignar id de persona
            $this->id_persona = $persona->id_persona;
        } else {
            // registrar datos de persona
            $persona = new Persona();
            $persona->numero_documento = $this->documento_identidad;
            $persona->apellido_paterno = mb_strtoupper($this->paterno, 'UTF-8');
            $persona->apellido_materno = mb_strtoupper($this->materno, 'UTF-8');
            $persona->nombre = mb_strtoupper($this->nombres, 'UTF-8');
            $persona->nombre_completo = mb_strtoupper($this->paterno . ' ' . $this->materno . ' ' . $this->nombres, 'UTF-8');
            $persona->id_genero = $this->genero;
            $persona->fecha_nacimiento = $this->fecha_nacimiento;
            $persona->direccion = mb_strtoupper($this->direccion, 'UTF-8');
            $persona->celular = $this->celular;
            if ($this->celular_opcional != null) {
                $persona->celular_opcional = $this->celular_opcional;
            } else {
                $persona->celular_opcional = null;
            }
            $persona->correo = mb_strtolower($this->email, 'UTF-8');
            if ($this->email_opcional != null) {
                $persona->correo_opcional = mb_strtolower($this->email_opcional, 'UTF-8');
            } else {
                $persona->correo_opcional = null;
            }
            $persona->año_egreso = $this->año_egreso;
            $persona->especialidad_carrera = mb_strtoupper($this->especialidad_carrera, 'UTF-8');
            $persona->centro_trabajo = mb_strtoupper($this->centro_trabajo, 'UTF-8');
            $persona->id_tipo_documento = $this->tipo_documento;
            $persona->id_discapacidad = $this->discapacidad;
            $persona->id_estado_civil = $this->estado_civil;
            $persona->id_grado_academico = $this->grado_academico;
            $persona->id_universidad = $this->universidad;
            $persona->ubigeo_direccion = $this->ubigeo_direccion;
            $persona->pais_direccion = $this->pais_direccion;
            $persona->ubigeo_nacimiento = $this->ubigeo_nacimiento;
            $persona->pais_nacimiento = $this->pais_nacimiento;
            $persona->save();

            // asignar id de persona
            $this->id_persona = $persona->id_persona;
        }

        // verificar si la persona ya se encuentra inscrita y evitar inscripciones duplicadas o multiples
        $inscripcion = Inscripcion::where('id_persona', $this->id_persona)
            ->where('id_programa_proceso', $this->programa)
            ->first();
        if ($inscripcion) {
            // emitir evento para mostrar mensaje de alerta de inscripcion duplicada
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'La persona ya se encuentra inscrita en el programa seleccionado',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // verificar si el pago ya se encuentra registrado
        $pago = Pago::where('pago_operacion', $this->numero_operacion)
            ->where('pago_documento', $this->documento_identidad)
            ->where('pago_monto', $this->monto_operacion)
            ->where('pago_fecha', $this->fecha_pago)
            ->first();
        if ($pago) {
            // emitir evento para mostrar mensaje de alerta de pago duplicado
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'El pago ya se encuentra registrado en el sistema',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // registrar datos deL pago
        $pago = new Pago();
        $pago->pago_documento = $this->documento_identidad;
        $pago->pago_operacion = $this->numero_operacion;
        $pago->pago_monto = $this->monto_operacion;
        $pago->pago_fecha = $this->fecha_pago;
        $pago->pago_estado = 2; // 1: pagado, 2: pendiente
        $pago->pago_verificacion = 1;
        if ($this->voucher) {
            $admision = Admision::where('admision_estado', 1)->first()->admision;

            $base_path = 'Posgrado/';
            $folders = [
                $admision,
                $this->documento_identidad,
                'Voucher'
            ];

            // Asegurar que se creen los directorios con los permisos correctos
            $path = asignarPermisoFolders($base_path, $folders);

            // Nombre del archivo
            $filename = 'voucher-pago.' . $this->voucher->getClientOriginalExtension();
            $nombre_db = $path . $filename;

            // Guardar el archivo
            $data = $this->voucher;
            $data->storeAs($path, $filename, 'files_publico');
            $pago->pago_voucher_url = $nombre_db;

            // Asignar todos los permisos al archivo
            chmod($nombre_db, 0777);
        }
        $pago->id_canal_pago = $this->canal_pago;
        $pago->id_concepto_pago = $this->concepto_pago;
        $pago->id_persona = $this->id_persona;
        $pago->save();

        //  obtener el ultimo codigo de inscripcion y creamos el nuevo codigo de acuerdo al año y convocatoria del proceso de admision
        $admision_año = Admision::where('admision_estado', 1)->first()->admision_año;
        $admision_año = substr($admision_año, -2);
        $admision_convocatoria = Admision::where('admision_estado', 1)->first()->admision_convocatoria;

        $ultimo_codifo_inscripcion = Inscripcion::orderBy('inscripcion_codigo', 'DESC')->first();
        if ($ultimo_codifo_inscripcion == null) {
            $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . '00001';
        } else {
            $codigo_inscripcion = $ultimo_codifo_inscripcion->inscripcion_codigo;
            if (substr($codigo_inscripcion, 2, 2) != $admision_año || substr($codigo_inscripcion, 4, 1) != $admision_convocatoria) {
                $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . '00001';
            } else {
                $codigo_inscripcion = substr($codigo_inscripcion, 5, 5);
                $codigo_inscripcion = intval($codigo_inscripcion) + 1;
                $codigo_inscripcion = str_pad($codigo_inscripcion, 5, "0", STR_PAD_LEFT);
                $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . $codigo_inscripcion;
            }
        }

        // registrar datos de inscripcion
        $inscripcion = new Inscripcion();
        $inscripcion->inscripcion_codigo = $codigo_inscripcion;
        $inscripcion->inscripcion_fecha = now();
        $inscripcion->id_persona = $this->id_persona;
        $inscripcion->inscripcion_estado = 0; // 0: pendiente, 1: inscrito
        if ($this->concepto_pago == getIdConceptoPagoConvenio() || $this->concepto_pago == getIdConceptoPagoVictimasViolencia()) {
            $inscripcion->es_convenio = 1;
        }
        $inscripcion->id_pago = $pago->id_pago;
        $inscripcion->id_programa_proceso = $this->programa;
        $inscripcion->inscripcion_tipo_programa = $this->mostrar_tipo_expediente;
        $inscripcion->es_traslado_externo = $this->concepto_pago == getIdTrasladoExterno() ? 1 : 0;
        $inscripcion->save();
        // asignar id de inscripcion
        $this->id_inscripcion = $inscripcion->id_inscripcion;

        // registramos los expedientes en la tabla expediente_inscripcion
        foreach ($this->expedientes as $key => $expediente) {
            // obtener el admision
            $admision = Admision::where('admision_estado', 1)->first()->admision;
            // registrar expedientes
            registrarExpedientes($admision, $this->documento_identidad, $expediente, $key, $inscripcion, 'crear');
        }

        // Sirve para asignar el seguimiento del expediente
        if ($this->check_expediente == true) {
            $exp_ins = ExpedienteInscripcion::join('expediente_admision', 'expediente_inscripcion.id_expediente_admision', '=', 'expediente_admision.id_expediente_admision')
                ->join('expediente', 'expediente_admision.id_expediente', '=', 'expediente.id_expediente')
                ->where('expediente_inscripcion.id_inscripcion', $this->id_inscripcion)
                ->where(function ($query) {
                    $query->where('expediente.expediente_tipo', 0)
                        ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                })
                ->get();
            $exp_seg = ExpedienteTipoSeguimiento::join('expediente', 'expediente_tipo_seguimiento.id_expediente', '=', 'expediente.id_expediente')
                ->where('expediente_tipo_seguimiento.expediente_tipo_seguimiento_estado', 1)
                ->where('expediente_tipo_seguimiento.id_tipo_seguimiento', 1)
                ->where(function ($query) {
                    $query->where('expediente.expediente_tipo', 0)
                        ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                })
                ->get();
            $array_seguimiento = [];
            foreach ($exp_ins as $exp) {
                foreach ($exp_seg as $seg) {
                    if ($exp->id_expediente == $seg->id_expediente) {
                        array_push($array_seguimiento, $exp->id_expediente_inscripcion);
                    }
                }
            }
            // Registrar datos del seguimiento del expediente de inscripcion
            foreach ($array_seguimiento as $item) {
                $seguimiento_exp_ins = new ExpedienteInscripcionSeguimiento();
                $seguimiento_exp_ins->id_expediente_inscripcion = $item;
                $seguimiento_exp_ins->tipo_seguimiento = 1;
                $seguimiento_exp_ins->expediente_inscripcion_seguimiento_estado = 1;
                $seguimiento_exp_ins->save();
            }
        }

        // Eliminar expedientes de la inscripcion que no son del programa elegido
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->get();
        // delete storage file and database
        foreach ($expediente_inscripcion as $exp) {
            $expediente = ExpedienteAdmision::join('expediente', 'expediente_admision.id_expediente', '=', 'expediente.id_expediente')
                ->where('expediente_admision.id_expediente_admision', $exp->id_expediente_admision)
                ->where(function ($query) {
                    $query->where('expediente.expediente_tipo', 0)
                        ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                })
                ->first();
            if ($expediente === null) {
                $exp->delete();
                File::delete($exp->expediente_inscripcion_url);
            }
        }

        // Registrar datos de usuario del estudiante
        $usuario_estudiante = UsuarioEstudiante::where('usuario_estudiante', $this->email)->first();
        if ($usuario_estudiante === null) {
            $usuario_estudiante = new UsuarioEstudiante();
            $usuario_estudiante->usuario_estudiante = $this->email;
            $usuario_estudiante->usuario_estudiante_password = Hash::make($persona->numero_documento);
            $usuario_estudiante->usuario_estudiante_creacion = now();
            $usuario_estudiante->usuario_estudiante_estado = 1;
            $usuario_estudiante->id_persona = $this->id_persona;
            $usuario_estudiante->save();
        } else {
            // falta mostrar mensaje de que el usuario ya existe y de que se le actualizo la contraseña
            $usuario_estudiante->usuario_estudiante_password = Hash::make($persona->numero_documento);
            $usuario_estudiante->save();
        }

        // alerta de cuenta regresiva
        $this->dispatchBrowserEvent('alerta_final_registro');

        // redireccionar a la pagina final
        return redirect()->route('inscripcion.pdf-email', ['id' => $this->id_inscripcion]);
    }

    public function render()
    {
        $tipo_documentos = TipoDocumento::where('tipo_documento_estado', 1)
            ->get();
        $canales_pagos = CanalPago::where('canal_pago_estado', 1)
            ->get();
        $conceptos_pagos = ConceptoPago::where('concepto_pago_estado', 1)
            ->whereIn('id_concepto_pago', getIdConceptoPagoInscripcion())
            ->get();
        $estado_civil_array = EstadoCivil::where('estado_civil_estado', 1)
            ->get();
        $tipo_discapacidad_array = Discapacidad::where('discapacidad_estado', 1)
            ->get();
        $universidad_array = Universidad::where('universidad_estado', 1)
            ->get();
        $grado_academico_array = GradoAcademico::where('grado_academico_estado', 1)
            ->get();
        $genero_array = Genero::where('genero_estado', 1)
            ->get();
        $tipo_seguimiento_constancia_sunedu = TipoSeguimiento::where('id_tipo_seguimiento', 1)
            ->first();
        return view('livewire.modulo-inscripcion.registro', [
            'tipo_documentos' => $tipo_documentos,
            'canales_pagos' => $canales_pagos,
            'conceptos_pagos' => $conceptos_pagos,
            'estado_civil_array' => $estado_civil_array,
            'tipo_discapacidad_array' => $tipo_discapacidad_array,
            'universidad_array' => $universidad_array,
            'grado_academico_array' => $grado_academico_array,
            'genero_array' => $genero_array,
            'tipo_seguimiento_constancia_sunedu' => $tipo_seguimiento_constancia_sunedu
        ]);
    }
}
