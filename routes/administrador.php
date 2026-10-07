<?php

use App\Http\Controllers\AdministradorController;
use App\Models\Persona;
use App\Models\Admitido;
use App\Models\Inscripcion;
use App\Models\UsuarioEstudiante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuloAdministrador\PagoController;
use App\Http\Controllers\ModuloAdministrador\PlanController;
use App\Http\Controllers\ModuloAdministrador\SedeController;
use App\Http\Controllers\ModuloAdministrador\CorreoController;
use App\Http\Controllers\ModuloAdministrador\AdmisionController;
use App\Http\Controllers\ModuloAdministrador\AdmitidoController;
use App\Http\Controllers\ModuloAdministrador\ProgramaController;
use App\Http\Controllers\ModuloAdministrador\CanalPagoController;
use App\Http\Controllers\ModuloAdministrador\DashboardController;
use App\Http\Controllers\ModuloCoordinador\CoordinadorController;
use App\Http\Controllers\ModuloAdministrador\EstudianteController;
use App\Http\Controllers\ModuloAdministrador\EvaluacionController;
use App\Http\Controllers\ModuloAdministrador\ExpedienteController;
use App\Http\Controllers\ModuloAdministrador\TrabajadorController;
use App\Http\Controllers\ModuloAdministrador\InscripcionController;
use App\Http\Controllers\ModuloAdministrador\ConceptoPagoController;
use App\Http\Controllers\ModuloAdministrador\InscripcionPagoController;
use App\Http\Controllers\ModuloAdministrador\TipoSeguimientoController;
use App\Http\Controllers\ModuloAdministrador\UsuarioTrabajadorController;
use App\Models\ActaDocente;
use App\Models\Docente;
use App\Models\Matricula as MatriculaOld;
use App\Models\Matricula\Matricula;
use App\Models\Matricula\MatriculaCurso;
use App\Models\Mensualidad;
use App\Models\NotaMatriculaCurso;
use App\Models\Trabajador;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

//Vista del Dashboard. El inicio la parte administrativa del sistema
Route::get('/', [DashboardController::class, 'dashboard'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.dashboard');

//Gestion de Usuarios
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/perfil', [DashboardController::class, 'perfil'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.perfil');
//Ruta para ir a la vista de Usuario en la Gestion de Usuarios
Route::get('/usuario', [UsuarioTrabajadorController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.usuario');
//Ruta para ir a la vista de Trabajador en la Gestion de Usuarios
Route::get('/trabajador', [TrabajadorController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.trabajador');

//Estudiantes
//Ruta para ir a la vista de Estudiantes
Route::get('/estudiante', [EstudianteController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.estudiante'); // No hay controlador

//Gestion Admision
//Ruta para ir a la vista de Admision en la Gestion Admision
Route::get('/admision', [AdmisionController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.admision');
//Ruta para ir a la vista de Inscripción en la Gestion Admision
Route::get('/inscripcion', [InscripcionController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.inscripcion');
//Ruta para ir a la vista de Evaluacion en la Gestion Admision
Route::get('/evaluacion', [EvaluacionController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.evaluacion');
//Inscripcion Pago en la Gestion Admision
Route::get('/inscripcion-pago', [InscripcionPagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.inscripcion-pago');
//Ruta para Admitidos en la Gestion Admision
Route::get('/admitidos', [AdmitidoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.admitidos');
//Ruta para la Gestionde Links de WhatsApp en la Gestion Admision
Route::get('/links-whatsapp', [AdmisionController::class, 'links_whatsapp'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.links-whatsapp');

//Gestion de Pagos
//Ruta para ir a la vista de Canal de Pago en la Gestion de Pagos
Route::get('/canal-pago', [CanalPagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.canal-pago'); // No hay controlador
//Ruta para ir a la vista de Concepto de Pago en la Gestion de Pagos
Route::get('/concepto-pago', [ConceptoPagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.concepto-pago'); // No hay controlador
//Ruta para ir a la vista de Pago en la Gestion de Pagos
Route::get('/pago', [PagoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.pago');

//Configuracion
//Ruta para ir a la vista de Programa en Configuracion
Route::get('/programa', [ProgramaController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.programa');
//Ruta para ir a la vista de Gestion de Plan y Proceso de Programa en Configuracion | Esta ruta de la vista está dentro de la vista de programa
Route::get('/programa/{id}/gestion-plan-proceso', [ProgramaController::class, 'plan_proceso'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.programa.gestion-plan-proceso');
//Ruta para ir a la vista de Plan en Configuracion
Route::get('/plan', [PlanController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.plan');
//Ruta para ir a la vista de Sede en Configuracion
Route::get('/sede', [SedeController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.sede');
//Ruta para ir a la vista de Expediente en Configuracion
Route::get('/expediente', [ExpedienteController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente');
//Ruta para ir a la vista de Gestion de Admision de Expediente en Configuracion | Esta ruta de la vista está dentro de la vista de expediente
Route::get('/expediente/{id}/gestion-admision', [ExpedienteController::class, 'admision'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente.gestion-admision');
//Ruta para ir a la vista de Gestion de Vistas para Evaluación de Expediente en Configuracion | Esta ruta de la vista está dentro de la vista de expediente
Route::get('/expediente/{id}/gestion-vistas-evaluacion', [ExpedienteController::class, 'evaluacion'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente.gestion-vistas-evaluacion');
//Ruta para ir a la vista de Gestion de Tipo de Seguimiento de Expediente en Configuracion | Esta ruta de la vista está dentro de la vista de expediente
Route::get('/expediente/{id}/gestion-tipo-seguimiento', [ExpedienteController::class, 'seguimiento'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.expediente.gestion-tipo-seguimiento');
//Ruta para ir a la vista de Tipo de Seguimiento en Configuracion
Route::get('/tipo-seguimiento', [TipoSeguimientoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.tipo-seguimiento');

// ruta para ir a la pagina de gestion de matriculas
Route::get('/gestion-matriculas', [AdministradorController::class, 'matriculas'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.matriculas');

// Ruta para generar fichas de inscripcion de quienes no se les genero y enviarlas por correo
Route::get('/generar-fichas-inscripcion', [InscripcionController::class, 'generarFichasInscripcion'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.generar-fichas-inscripcion');

// Ruta para el modulo de gestion de correos
Route::get('/gestion-correo', [CorreoController::class, 'index'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.gestion-correo');
Route::get('/gestion-correo/crear', [CorreoController::class, 'create'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.gestion-correo.crear');
Route::post('gestion-correo/upload', [CorreoController::class, 'upload'])->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('ckeditor-upload-file');

// ruta para ir a la pagina de gestion de reingreso invividual
Route::get('/gestion-reingreso/individual', [CoordinadorController::class, 'reingreso_individual'])
    ->middleware(['auth.usuario', 'verificar.usuario.administrador'])
    ->name('administrador.reingreso.individual');

// ruta para ir a la pagina de gestion de reingreso masivo
Route::get('/gestion-reingreso/masivo', [CoordinadorController::class, 'reingreso_masivo'])
    ->middleware(['auth.usuario', 'verificar.usuario.administrador'])
    ->name('administrador.reingreso.masivo');

// ruta para ir a la pagina de gestion de retiro
Route::get('/gestion-retiro', [CoordinadorController::class, 'retiro'])
    ->middleware(['auth.usuario', 'verificar.usuario.administrador'])
    ->name('administrador.retiro');

// ruta para generar numeros consecutivos por programa academico de las inscripciones verificadas
Route::get('/generar-numero-consecutivo', function () {
    // solo se numeran las inscripciones del proceso de admision activo
    $id_admision = getAdmision()->id_admision;

    $programas = Inscripcion::join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
            ->where('programa.programa_estado', 1)
            ->where('programa_proceso.id_admision', $id_admision)
            ->where('inscripcion.inscripcion_estado', 1)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.verificar_expedientes', 1)
            ->distinct()
            ->select('programa_proceso.id_programa_proceso', 'programa.programa', 'programa.subprograma', 'programa.mencion')
            ->get();

    foreach ($programas as $programa) {
        $inscripciones = Inscripcion::join('programa_proceso','inscripcion.id_programa_proceso','=','programa_proceso.id_programa_proceso')
            ->join('programa_plan','programa_proceso.id_programa_plan','=','programa_plan.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->join('persona','inscripcion.id_persona','=','persona.id_persona')
            ->where('programa.programa_estado',1)
            ->where('programa_proceso.id_admision', $id_admision)
            ->where('inscripcion.inscripcion_estado',1)
            ->where('inscripcion.retiro_inscripcion',0)
            ->where('inscripcion.verificar_expedientes',1)
            ->where('inscripcion.id_programa_proceso',$programa->id_programa_proceso)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        $numero = 1;
        foreach ($inscripciones as $inscripcion) {
            $inscripcion->numero = $numero;
            $inscripcion->save();
            $numero++;
        }
    }

    return response()->json([
        'message' => 'Numeros consecutivos generados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.generar-numero-consecutivo');


// cambiar todos los correos de los usuarios a minusculas
Route::get('/cambiar-correos', function () {
    $usuarios = UsuarioEstudiante::all();
    foreach ($usuarios as $usuario) {
        $persona = Persona::where('id_persona', $usuario->id_persona)->first();
        $usuario->usuario_estudiante = mb_strtolower($persona->correo, 'UTF-8');
        $usuario->save();
    }
    return response()->json([
        'message' => 'Correos cambiados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.cambiar-correos');

// verificar si hay expedientes pendientes por verificar
Route::get('/verificar-expedientes-pendientes', function () {
    $inscripciones = Inscripcion::all();
    foreach ($inscripciones as $inscripcion) {
        // verificar si tiene expedientes pendientes por verificar, si tienes expedientes pendientes por verificar, el estado es 0
        // si el estado es 1, significa que todos los expedientes han sido verificados y si el estado es 2, significa que hay expedientes observados
        $estado = verEstadoExpediente($inscripcion->id_inscripcion);

        // cambiar el estado de la verificacion de expedientes
        $inscripcion->verificar_expedientes = $estado;
        $inscripcion->save();
    }
    return response()->json([
        'message' => 'Expedientes verificados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.verificar-expedientes-pendientes');

// verificar si la persona tiene usuario estudiante creado y si tiene modificar su contraseña a su numero de documento
Route::get('/verificar-usuario-estudiante', function () {
    $personas = Persona::all();
    foreach ($personas as $persona) {
        $usuario = UsuarioEstudiante::where('id_persona', $persona->id_persona)->first();
        if (!$usuario) {
            $usuario = new UsuarioEstudiante();
            $usuario->usuario_estudiante = mb_strtolower($persona->correo, 'UTF-8');
            $usuario->usuario_estudiante_password = Hash::make($persona->numero_documento);
            $usuario->usuario_estudiante_creacion = date('Y-m-d H:i:s');
            $usuario->usuario_estudiante_estado = 1;
            $usuario->id_persona = $persona->id_persona;
            $usuario->usuario_estudiante_perfil_url = null;
            $usuario->save();
        }
    }
    return response()->json([
        'message' => 'Usuarios estudiante verificados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.verificar-usuario-estudiante');

Route::get('/generar-es-traslado-externo-admitidos', function () {
    $inscripciones = Inscripcion::where('es_traslado_externo', 1)->get();
    foreach ($inscripciones as $inscripcion) {
        $admitido = Admitido::where('id_persona', $inscripcion->id_persona)
            ->where('id_programa_proceso', $inscripcion->id_programa_proceso)
            ->first();
        if ($admitido) {
            $admitido->es_traslado_externo = 1;
            $admitido->save();
        }
    }
    return response()->json([
        'message' => 'Admitidos actualizados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.generar-es-traslado-externo-admitidos');

// convertir los nombres y apellidos de los trabajadores docentes a mayusculas
Route::get('/convertir-nombres-docentes-mayusculas', function () {
    $docentes = Docente::join('trabajador', 'trabajador.id_trabajador', '=', 'docente.id_trabajador')
        ->get();
    foreach ($docentes as $item) {
        $trabajador = Trabajador::find($item->id_trabajador);
        $trabajador->trabajador_apellido = mb_strtoupper($item->trabajador_apellido, 'UTF-8');
        $trabajador->trabajador_nombre = mb_strtoupper($item->trabajador_nombre, 'UTF-8');
        $trabajador->trabajador_nombre_completo = mb_strtoupper($item->trabajador_nombre_completo, 'UTF-8');
        $trabajador->save();
    }
    return response()->json([
        'message' => 'Nombres y apellidos de los trabajadores docentes convertidos a mayusculas'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.convertir-nombres-apellidos-trabajadores-docentes');

// configurar el nombre de correo de los usuarios docentes a su primer nombre _ primer apellido
Route::get('/configurar-correo-docentes', function () {
    $usuarios = Usuario::join('trabajador_tipo_trabajador', 'usuario.id_trabajador_tipo_trabajador', '=', 'trabajador_tipo_trabajador.id_trabajador_tipo_trabajador')
        ->join('trabajador', 'trabajador_tipo_trabajador.id_trabajador', '=', 'trabajador.id_trabajador')
        ->where('trabajador_tipo_trabajador.id_tipo_trabajador', 1)
        ->get();
    foreach ($usuarios as $usuario) {
        // extraemos el primer nombre, primer apellido y los dos ultimos digitos del documento para generar su correo electronico de usuario
        $nombre = explode(' ', $usuario->trabajador_nombre);
        $apellido = explode(' ', $usuario->trabajador_apellido);
        $correo = strtolower($nombre[0].'_'.$apellido[0].'@unu.edu.pe');
        $usuario->usuario_nombre = $usuario->trabajador_nombre_completo;
        $usuario->usuario_correo = $correo;
        $usuario->save();
    }
    return response()->json([
        'message' => 'Correos de los docentes configurados'
    ]);
})->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.configurar-correo-docentes');

// Rutas para la migracion de las tablas de matricula, etc.

// Route::get('/migrar-matriculas', function() {

//     try {
//         DB::beginTransaction();

//         $matriculasOld = MatriculaOld::all();

//         foreach ($matriculasOld as $itemMatriculaOld) {
//             // registramos los datos de la matriculaOld a la nueva tabla
//             $matricula = new Matricula();
//             $matricula->id_matricula_gestion = null;
//             $matricula->id_admitido = $itemMatriculaOld->id_admitido;
//             $matricula->ciclo = $itemMatriculaOld->matricula_estado == 1 ? 1 : 0;
//             $matricula->codigo = $itemMatriculaOld->matricula_codigo;
//             $matricula->fecha_matricula = date('Y-m-d', strtotime($itemMatriculaOld->matricula_fecha_creacion));
//             $matricula->creditos_disponibles = 22;
//             $matricula->id_pago = $itemMatriculaOld->id_pago;
//             $matricula->estado = 1;
//             $matricula->save();

//             // obtenemos los cursos de la matriculaOld
//             $matriculaCursosOld = $itemMatriculaOld->matriculaCursos;

//             foreach ($matriculaCursosOld as $itemMatriculaCursoOld) {
//                 // obtenemos la ultima nota de la matricula cursoOld
//                 $notaMatriculaCursoOld = NotaMatriculaCurso::query()
//                     ->where('id_matricula_curso', $itemMatriculaCursoOld->id_matricula_curso)
//                     ->orderBy('id_nota_matricula_curso', 'desc')
//                     ->first();

//                 // registramos los datos de la matricula cursoOld a la nueva tabla
//                 $matriculaCurso = new MatriculaCurso();
//                 $matriculaCurso->id_matricula = $matricula->id_matricula;
//                 $matriculaCurso->id_curso_programa_plan = $itemMatriculaCursoOld->id_curso_programa_plan;
//                 $matriculaCurso->id_programa_proceso_grupo = $itemMatriculaOld->id_programa_proceso_grupo;
//                 $matriculaCurso->id_docente = $notaMatriculaCursoOld ? $notaMatriculaCursoOld->id_docente : null;
//                 $matriculaCurso->periodo = $itemMatriculaOld->matricula_proceso;
//                 $matriculaCurso->es_acta_adicional = $itemMatriculaCursoOld->acta_adicional;
//                 $matriculaCurso->es_acta_reingreso = $itemMatriculaCursoOld->acta_reingreso;
//                 $matriculaCurso->es_acta_incorporacion = $itemMatriculaCursoOld->acta_reincorporacion;
//                 $matriculaCurso->nota_evaluacion_permanente = $notaMatriculaCursoOld ? $notaMatriculaCursoOld->nota_evaluacion_permanente : null;
//                 $matriculaCurso->nota_evaluacion_medio_curso = $notaMatriculaCursoOld ? $notaMatriculaCursoOld->nota_evaluacion_medio_curso : null;
//                 $matriculaCurso->nota_evaluacion_final = $notaMatriculaCursoOld ? $notaMatriculaCursoOld->nota_evaluacion_final : null;
//                 $matriculaCurso->nota_promedio_final = $notaMatriculaCursoOld ? $notaMatriculaCursoOld->nota_promedio_final : null;
//                 $matriculaCurso->nota_observacion = $notaMatriculaCursoOld ? $notaMatriculaCursoOld->nota_observacion : null;

//                 // verificamos si el docente ya ingreso las notas para obtener la fecha de ingreso de notas segun el acta docente
//                 $fechaIngresoNota = $notaMatriculaCursoOld ? date('Y-m-d', strtotime($notaMatriculaCursoOld->created_at)) : null;
//                 if ($matriculaCurso->id_docente) {
//                     $actaDocente = ActaDocente::query()
//                         ->with([
//                             'docente_curso' => function ($query) use ($matriculaCurso) {
//                                 $query->with([
//                                     'docente' => function ($query) use ($matriculaCurso) {
//                                         $query->with('trabajador')
//                                             ->where('id_docente', $matriculaCurso->id_docente);
//                                     },
//                                     'curso_programa_plan' => function ($query) use ($matriculaCurso) {
//                                         $query->with('curso')
//                                             ->where('id_curso_programa_plan', $matriculaCurso->id_curso_programa_plan);
//                                     }
//                                 ]);
//                             }
//                         ])
//                         ->whereHas('docente_curso', function ($query) use ($matriculaCurso) {
//                             $query->where('id_docente', $matriculaCurso->id_docente)
//                                 ->where('id_curso_programa_plan', $matriculaCurso->id_curso_programa_plan);
//                         })
//                         ->first();

//                     $fechaIngresoNota = $actaDocente ? date('Y-m-d', strtotime($actaDocente->acta_docente_fecha_creacion)) : null;
//                 }

//                 $matriculaCurso->fecha_ingreso_nota = $fechaIngresoNota;

//                 // verificamos el estado de la matricula curso segun la tabla estado_cursos de la matriculaOld
//                 if ($notaMatriculaCursoOld) {
//                     if ($notaMatriculaCursoOld->id_estado_cursos == 1) {
//                         $matriculaCurso->estado = 2;
//                     } else if ($notaMatriculaCursoOld->id_estado_cursos == 2) {
//                         $matriculaCurso->estado = 0;
//                     } else if ($notaMatriculaCursoOld->id_estado_cursos == 3) {
//                         $matriculaCurso->estado = 0;
//                     } else if ($notaMatriculaCursoOld->id_estado_cursos == 4) {
//                         $matriculaCurso->estado = 3;
//                     }
//                 } else {
//                     $matriculaCurso->estado = 1;
//                 }

//                 $matriculaCurso->activo = $itemMatriculaCursoOld->matricula_curso_activo;
//                 $matriculaCurso->save();
//             }

//             // registramos los nuevos id de las matriculas en la tabla mensualidad
//             $mensualidades = Mensualidad::query()
//                 ->where('id_matricula_old', $itemMatriculaOld->id_matricula)
//                 ->get();

//             foreach ($mensualidades as $mensualidad) {
//                 $mensualidad->id_matricula = $matricula->id_matricula;
//                 $mensualidad->save();
//             }

//             // calculamos los creditos acumulados del estudiante
//             calcularCreditosAcumulados($itemMatriculaOld->id_admitido);
//         }

//         DB::commit();
//     } catch (\Exception $e) {
//         DB::rollBack();
//         dd($e);
//     }

//     return response()->json([
//         'message' => 'Matriculas migradas'
//     ]);
// })->middleware(['auth.usuario', 'verificar.usuario.administrador'])->name('administrador.migrar-matriculas');

//
