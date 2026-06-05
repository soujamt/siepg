<?php

use App\Models\Admision;
use App\Models\Admitido;
use App\Models\CostoEnseñanza;
use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\Mensualidad;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\ProgramaProceso;
use App\Models\Matricula\Matricula as ModelMatricula;
use App\Models\Matricula\MatriculaCurso as ModelMatriculaCurso;
use App\Models\Reincorporacion;
use App\Models\Reingreso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

function getAdmision()
{
    return Admision::where('admision_estado', 1)->first();
}

function convertirNumeroARomano($numero)
{
    $numero = (int) $numero;

    if ($numero <= 0) {
        return null;
    }

    $mapa = [
        1000 => 'M',
        900 => 'CM',
        500 => 'D',
        400 => 'CD',
        100 => 'C',
        90 => 'XC',
        50 => 'L',
        40 => 'XL',
        10 => 'X',
        9 => 'IX',
        5 => 'V',
        4 => 'IV',
        1 => 'I',
    ];

    $romano = '';

    foreach ($mapa as $valor => $simbolo) {
        while ($numero >= $valor) {
            $romano .= $simbolo;
            $numero -= $valor;
        }
    }

    return $romano;
}

function formatearAdmisionVisual($admision)
{
    if (!$admision) {
        return $admision;
    }

    if (!preg_match('/^(.*?\b\d{4})\s*-\s*(\d+)\s*$/u', $admision, $coincidencias)) {
        return $admision;
    }

    $convocatoriaRomana = convertirNumeroARomano($coincidencias[2]);

    if (!$convocatoriaRomana) {
        return $admision;
    }

    return rtrim($coincidencias[1]) . ' - ' . $convocatoriaRomana;
}

function convertirFechaHora($fechaHora)
{
    // formato de fecha y hora: 12:00 pm - 12/12/2012
    return date('h:i a d/m/Y', strtotime($fechaHora));
}

function getIdTrasladoExterno()
{
    return 8; // id de traslado externo
}

function getIdConceptoPagoInscripcion()
{
    return [
        1, // id de concepto de pago de inscripcion
        8, // id de concepto de pago de inscripcion para traslado externo
        9, // id de concepto de pago de inscripcion para CONVENIO
        10 // id de concepto de pago de inscripcion para VICTIMAS DE LA VIOLENCIA
    ];
}

function getIdConceptoPagoConvenio()
{
    return 9; // id de concepto de pago de convenio
}

function getIdConceptoPagoVictimasViolencia()
{
    return 10; // id de concepto de pago de victimas de la violencia
}

function asignarPermisoFolders($base_path, $folders)
{
    $path = $base_path;
    foreach ($folders as $folder) {
        $path .= $folder . '/';
        // Asegurar que se creen los directorios con los permisos correctos
        $parent_directory = dirname($path);
        if (!file_exists($parent_directory)) {
            mkdir($parent_directory, 0777, true); // Establecer permisos en el directorio padre
        }
        if (!file_exists($path)) {
            mkdir($path, 0777, true); // 0777 establece todos los permisos para el directorio
            // Cambiar el modo de permisos después de crear los directorios
            chmod($path, 0777);
        }
    }
    return $path;
}

function registrarExpedientes($admision, $numero_documento, $expediente, $key, $inscripcion, $modo)
{
    $expediente_model = ExpedienteAdmision::where('expediente_admision_estado', 1)->where('id_expediente_admision', $key)->first();

    // Crear directorios para guardar los archivos
    $base_path = 'Posgrado/';
    $folders = [
        $admision,
        $numero_documento,
        'Expedientes'
    ];

    // Asegurar que se creen los directorios con los permisos correctos
    $path = asignarPermisoFolders($base_path, $folders);

    // Nombre del archivo
    $filename = $expediente_model->expediente->expediente_nombre_file . ".pdf";
    $nombre_db = $path . $filename;

    // Guardar el archivo
    $expediente->storeAs($path, $filename, 'files_publico');

    // Asignar todos los permisos al archivo
    chmod($nombre_db, 0777);

    if ($modo == 'crear') {
        // Registrar datos del expediente de inscripcion
        $expediente_inscripcion = new ExpedienteInscripcion();
        $expediente_inscripcion->expediente_inscripcion_url = $nombre_db;
        $expediente_inscripcion->expediente_inscripcion_estado = 1;
        $expediente_inscripcion->expediente_inscripcion_verificacion = 0;
        $expediente_inscripcion->expediente_inscripcion_fecha = now();
        $expediente_inscripcion->id_expediente_admision = $key;
        $expediente_inscripcion->id_inscripcion = $inscripcion->id_inscripcion;
        $expediente_inscripcion->save();
    } else if ($modo == 'editar') {
        // Actualizar datos del expediente de inscripcion
        $expediente_inscripcion = ExpedienteInscripcion::where('id_expediente_admision', $key)->where('id_inscripcion', $inscripcion->id_inscripcion)->first();
        $expediente_inscripcion->expediente_inscripcion_url = $nombre_db;
        $expediente_inscripcion->expediente_inscripcion_estado = 1;
        $expediente_inscripcion->expediente_inscripcion_verificacion = 0;
        $expediente_inscripcion->expediente_inscripcion_fecha = now();
        $expediente_inscripcion->save();
    }

    // cambiar el estado de la verificacion de expedientes de la inscripcion a pendiente
    $inscripcion = Inscripcion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
    $inscripcion->verificar_expedientes = 0;
    $inscripcion->save();
}

function verEstadoExpediente($id_inscripcion)
{
    $expedientes = ExpedienteInscripcion::where('id_inscripcion', $id_inscripcion)->get();
    $estado = 0;

    foreach ($expedientes as $expediente) {
        if ($expediente->expediente_inscripcion_verificacion == 0) {
            $estado = 0;
            break;
        } elseif ($expediente->expediente_inscripcion_verificacion == 2) {
            $estado = 2;
            break;
        } else {
            $estado = 1;
        }
    }

    return $estado;
}

function verificarProcesoAdmision()
{
    $admision = Admision::where('admision_estado', 1)->first();
    if ($admision->admision_fecha_inicio_inscripcion <= date('Y-m-d') && $admision->admision_fecha_fin_inscripcion >= date('Y-m-d')) {
        return true;
    } else {
        return false;
    }
}

function calcularCantidadDePersonas($tipo, $proceso, $modalidad, $programa)
{
    $cantidad = collect();

    if ($tipo == 1) {
        if ($proceso && $modalidad && $programa) {
            $cantidad = Inscripcion::query()
                ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                ->where('programa_proceso.id_admision', $proceso)
                ->where('programa.id_modalidad', $modalidad)
                ->where('programa.id_programa', $programa)
                ->get();
        }
        if ($proceso && $modalidad && !$programa) {
            $cantidad = Inscripcion::query()
                ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                ->where('programa_proceso.id_admision', $proceso)
                ->where('programa.id_modalidad', $modalidad)
                ->get();
        }
        if ($proceso && !$modalidad && !$programa) {
            $cantidad = Inscripcion::query()
                ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                ->where('programa_proceso.id_admision', $proceso)
                ->get();
        }
    } else {
        if ($proceso && $modalidad && $programa) {
            $cantidad = Admitido::query()
                ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'admitido.id_programa_proceso')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('persona', 'persona.id_persona', '=', 'admitido.id_persona')
                ->where('programa_proceso.id_admision', $proceso)
                ->where('programa.id_modalidad', $modalidad)
                ->where('programa.id_programa', $programa)
                ->get();
        }
        if ($proceso && $modalidad && !$programa) {
            $cantidad = Admitido::query()
                ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'admitido.id_programa_proceso')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('persona', 'persona.id_persona', '=', 'admitido.id_persona')
                ->where('programa_proceso.id_admision', $proceso)
                ->where('programa.id_modalidad', $modalidad)
                ->get();
        }
        if ($proceso && !$modalidad && !$programa) {
            $cantidad = Admitido::query()
                ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'admitido.id_programa_proceso')
                ->join('persona', 'persona.id_persona', '=', 'admitido.id_persona')
                ->where('programa_proceso.id_admision', $proceso)
                ->get();
        }
    }

    $correos = [];

    if ($tipo == 1) {
        foreach ($cantidad as $item) {
            $correos[] = $item->correo;
        }
    } else {
        foreach ($cantidad as $item) {
            $correos[] = $item->correo;
        }
    }

    return [
        'cantidad' => $cantidad->count(),
        'correos' => $correos
    ];
}

function generarFichaInscripcion($id_inscripcion)
{
    $id = $id_inscripcion;
    $inscripcion = Inscripcion::where('id_inscripcion', $id)->first(); // Datos de la inscripcion

    $pago = Pago::where('id_pago', $inscripcion->id_pago)->first();
    $pago_monto = $pago->pago_monto; // Monto del pago

    $admision = $inscripcion->programa_proceso->admision->admision; // Admision de la inscripcion

    $fecha_actual = date('h:i:s a d/m/Y', strtotime($inscripcion->inscripcion_fecha)); // Fecha de inscripcion
    $fecha_actual2 = date('d-m-Y', strtotime($inscripcion->inscripcion_fecha)); // Fecha de inscripcion
    $programa = ProgramaProceso::where('id_programa_proceso', $inscripcion->id_programa_proceso)->first(); // Programa de la inscripcion
    $inscripcion_codigo = Inscripcion::where('id_inscripcion', $id)->first()->inscripcion_codigo;
    $tiempo = 6;
    $valor = '+ ' . intval($tiempo) . ' month';
    setlocale(LC_ALL, "es_ES@euro", "es_ES", "esp");
    $final = strftime('%d de %B del %Y', strtotime($fecha_actual2 . $valor));
    $persona = Persona::where('id_persona', $inscripcion->id_persona)->first();
    $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $id)->get();
    $expediente = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', '=', 'expediente_admision.id_expediente')
        ->join('admision', 'admision.id_admision', '=', 'expediente_admision.id_admision')
        ->where('expediente_admision.expediente_admision_estado', 1)
        ->where('expediente.expediente_estado', 1)
        ->where('admision.admision_estado', 1)
        ->where(function ($query) use ($inscripcion) {
            $query->where('expediente.expediente_tipo', 0)
                ->orWhere('expediente.expediente_tipo', $inscripcion->inscripcion_tipo_programa);
        })
        ->get();

    // verificamos si tiene expediente en seguimientos
    $seguimiento_count = ExpedienteInscripcionSeguimiento::join('expediente_inscripcion', 'expediente_inscripcion.id_expediente_inscripcion', '=', 'expediente_inscripcion_seguimiento.id_expediente_inscripcion')
        ->where('expediente_inscripcion.id_inscripcion', $id)
        ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
        ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
        ->count();

    $data = [
        'persona' => $persona,
        'fecha_actual' => $fecha_actual,
        'programa' => $programa,
        'admision' => $admision,
        'pago' => $pago,
        'inscripcion' => $inscripcion,
        'inscripcion_codigo' => $inscripcion_codigo,
        'pago_monto' => $pago_monto,
        'expediente_inscripcion' => $expediente_inscripcion,
        'expediente' => $expediente,
        'seguimiento_count' => $seguimiento_count
    ];

    // Crear directorios para guardar los archivos
    $base_path = 'Posgrado/';
    $folders = [
        $admision,
        $persona->numero_documento,
        'Expedientes'
    ];

    // Asegurar que se creen los directorios con los permisos correctos
    $path = asignarPermisoFolders($base_path, $folders);

    // Crear el directorio si no existe
    $fullPath = public_path($path);
    if (!file_exists($fullPath)) {
        if (!mkdir($fullPath, 0777, true) && !is_dir($fullPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $fullPath));
        }
    }

    // si existe el archivo, eliminarlo y crear uno nuevo
    if ($inscripcion->inscripcion_ficha_url) {
        if (file_exists($inscripcion->inscripcion_ficha_url)) {
            unlink($inscripcion->inscripcion_ficha_url);
        }
    }

    // Nombre del archivo
    $nombre_pdf = 'ficha-inscripcion-' . Str::slug($persona->nombre_completo, '-') . '.pdf';
    $nombre_db = $path . $nombre_pdf;

    // Generar el pdf de inscripcion
    PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data)->save($fullPath . '/' . $nombre_pdf);

    $inscripcion = Inscripcion::find($id);
    $inscripcion->inscripcion_ficha_url = $nombre_db;
    $inscripcion->save();

    // Asignar todos los permisos al archivo
    chmod($nombre_db, 0777);
}

function finalizar_evaluacion($evaluacion, $puntaje)
{
    if ($evaluacion->id_tipo_evaluacion == 1) {
        $puntaje_final = $evaluacion->puntaje_expediente + $evaluacion->puntaje_entrevista;
        if ($evaluacion->puntaje_expediente && $evaluacion->puntaje_entrevista) {
            if ($puntaje->puntaje_maestria <= $puntaje_final) {
                $evaluacion->evaluacion_observacion = null;
                $evaluacion->evaluacion_estado = 2; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            } else {
                $evaluacion->evaluacion_observacion = 'El puntaje total no supera el puntaje mínimo.';
                $evaluacion->evaluacion_estado = 3; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            }
            $evaluacion->puntaje_final = $puntaje_final;
            $evaluacion->save();
        }
    } else if ($evaluacion->id_tipo_evaluacion == 2) {
        $puntaje_final = $evaluacion->puntaje_expediente + $evaluacion->puntaje_investigacion + $evaluacion->puntaje_entrevista;
        if ($evaluacion->puntaje_expediente && $evaluacion->puntaje_investigacion && $evaluacion->puntaje_entrevista) {
            if ($puntaje->puntaje_doctorado <= $puntaje_final) {
                $evaluacion->evaluacion_observacion = null;
                $evaluacion->evaluacion_estado = 2; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            } else {
                $evaluacion->evaluacion_observacion = 'El puntaje total no supera el puntaje mínimo.';
                $evaluacion->evaluacion_estado = 3; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            }
            $evaluacion->puntaje_final = $puntaje_final;
            $evaluacion->save();
        }
    }

    if ($puntaje_final == 0) {
        $evaluacion->evaluacion_observacion = 'No se presentó a la evaluación de entrevista.';
        $evaluacion->save();
    }
}

function dataPagoMatricula($id_admitido, $id_matricula = null)
{
    if ($id_matricula) {
        $monto_total = calcularMontoTotalCostoPorEnsenhanzaEstudiante($id_admitido, $id_matricula);
        $monto_pagado = calcularMontoPagadoCostoPorEnsenhanzaEstudiante($id_admitido, $id_matricula);
    } else {
        $monto_total = calcularMontoTotalCostoPorEnsenhanzaEstudiante($id_admitido);
        $monto_pagado = calcularMontoPagadoCostoPorEnsenhanzaEstudiante($id_admitido);
    }
    $deuda = $monto_total - $monto_pagado;

    return [
        'monto_total' => $monto_total,
        'monto_pagado' => $monto_pagado,
        'deuda' => $deuda
    ];
}

function calcularMontoTotalCostoPorEnsenhanzaEstudiante($id_admitido, $id_matricula = null)
{
    $admitido = Admitido::query()
        ->with('ultimaMatricula')
        ->find($id_admitido);

    if ($id_matricula) {
        $ultima_matricula = ModelMatricula::query()
            ->find($id_matricula);
    } else {
        $ultima_matricula = $admitido->ultimaMatriculaNuevo;
    }

    if (!$ultima_matricula) {
        return 0;
    }

    $cursos = $ultima_matricula->cursos()
        ->with([
            'cursoProgramaPlan' => function ($query) {
                $query->with('curso');
            }
        ])
        ->get();

    $creditos_totales = 0;

    foreach ($cursos as $curso) {
        $creditos_totales += $curso->cursoProgramaPlan->curso->curso_credito;
    }

    $costo_enseñanza = CostoEnseñanza::query()
        ->where('id_plan', $admitido->programa_proceso->programa_plan->id_plan)
        ->where('programa_tipo', $admitido->programa_proceso->programa_plan->programa->programa_tipo)
        ->first();

    $monto_total = $costo_enseñanza->costo_credito * $creditos_totales;

    return $monto_total;
}

function calcularMontoPagadoCostoPorEnsenhanzaEstudiante($id_admitido, $id_matricula = null)
{
    $admitido = Admitido::query()
        ->with('ultimaMatricula')
        ->find($id_admitido);

    if ($id_matricula) {
        $ultima_matricula = ModelMatricula::query()
            ->find($id_matricula);
    } else {
        $ultima_matricula = $admitido->ultimaMatriculaNuevo;
    }

    if (!$ultima_matricula) {
        return 0;
    }

    $mensualidades = Mensualidad::query()
        ->with([
            'matricula',
            'pago'
        ])
        ->where('id_admitido', $id_admitido)
        ->where('id_matricula', $ultima_matricula->id_matricula)
        ->get();

    $monto_pagado = 0;

    foreach($mensualidades as $mensualidad)
    {
        if ( $mensualidad->pago ) {
            if ( $mensualidad->pago->pago_estado == 2 && $mensualidad->pago->pago_verificacion == 2 )
            {
                $monto_pagado += $mensualidad->pago->pago_monto;
            }
        }
    }

    return $monto_pagado;
}

function calcularCicloEstudiante($id_admitido)
{
    $ciclo = 0;

    $admitido = Admitido::query()
        ->with('ultimaMatriculaNuevo')
        ->find($id_admitido);

    $ultima_matricula = $admitido->ultimaMatriculaNuevo;

    if (!$ultima_matricula) {
        return 1;
    }

    $ciclosTotalesDelPrograma = $admitido->programa_proceso->programa_plan->programa->duracion_ciclos;

    $ciclo = $ultima_matricula->ciclo;

    if ($ciclo >= $ciclosTotalesDelPrograma) {
        $ciclo = $ciclosTotalesDelPrograma;
    } else {
        $ciclo++;
    }

    return $ciclo;
}

function obtenerContadorDeMatriculasPorGrupos($id_programa_proceso, $id_matricula_gestion, $id_programa_proceso_grupo)
{
    $matriculados = ModelMatricula::query()
        ->with([
            'admitido' => function($query) use ($id_programa_proceso) {
                $query->with('persona', 'programa_proceso')
                    ->where('id_programa_proceso', $id_programa_proceso);
            },
            'cursos'
        ])
        ->where('id_matricula_gestion', $id_matricula_gestion)
        ->where('estado', 1)
        ->get();
    $contador = 0;
    foreach($matriculados as $matriculado) {
        if ($matriculado->admitido) {
            $value = false;
            foreach($matriculado->cursos as $curso) {
                if ($curso->id_programa_proceso_grupo == $id_programa_proceso_grupo) {
                    $value = true;
                }
            }
            if ($value) {
                $contador++;
            }
        }
    }
    return $contador;
}

function obtenerContadorDeMatriculasPorGruposIngresantes($id_programa_proceso, $id_programa_proceso_grupo)
{
    $matriculados = ModelMatricula::query()
        ->with([
            'admitido' => function($query) use ($id_programa_proceso) {
                $query->with('persona', 'programa_proceso')
                    ->where('id_programa_proceso', $id_programa_proceso);
            },
            'cursos'
        ])
        ->where('ciclo', 1)
        ->where('estado', 1)
        ->get();
    $contador = 0;
    foreach($matriculados as $matriculado) {
        if ($matriculado->admitido) {
            $value = false;
            foreach($matriculado->cursos as $curso) {
                if ($curso->id_programa_proceso_grupo == $id_programa_proceso_grupo) {
                    $value = true;
                }
            }
            if ($value) {
                $contador++;
            }
        }
    }
    return $contador;
}

function obtenerGrupoDeMatricula($id_matricula)
{
    $matricula = ModelMatricula::query()
        ->with('cursos')
        ->where('id_matricula', $id_matricula)
        ->first();

    if (!$matricula) {
        return '';
    }

    $cursos = $matricula->cursos()
        ->with('programaProcesoGrupo')
        ->get();

    $grupo_counts = [];
    foreach ($cursos as $curso) {
        $grupo_detalle = $curso->programaProcesoGrupo->grupo_detalle;
        if (!isset($grupo_counts[$grupo_detalle])) {
            $grupo_counts[$grupo_detalle] = 0;
        }
        $grupo_counts[$grupo_detalle]++;
    }

    $grupo = null;
    foreach ($grupo_counts as $grupo_detalle => $count) {
        if ($count >= 2) {
            $grupo = $grupo_detalle;
            break;
        }
    }

    if ($grupo === null && !empty($grupo_counts)) {
        $grupo = array_key_first($grupo_counts);
    }

    return $grupo ?? '';
}

function obtenerIdGrupoDeMatricula($id_matricula)
{
    $matricula = ModelMatricula::query()
        ->with('cursos')
        ->where('id_matricula', $id_matricula)
        ->first();

    if (!$matricula) {
        return '';
    }

    $cursos = $matricula->cursos()
        ->with('programaProcesoGrupo')
        ->get();

    $grupo_counts = [];
    foreach ($cursos as $curso) {
        $id_grupo = $curso->programaProcesoGrupo->id_programa_proceso_grupo;
        if (!isset($grupo_counts[$id_grupo])) {
            $grupo_counts[$id_grupo] = 0;
        }
        $grupo_counts[$id_grupo]++;
    }

    $grupo = null;
    foreach ($grupo_counts as $id_grupo => $count) {
        if ($count >= 2) {
            $grupo = $id_grupo;
            break;
        }
    }

    if ($grupo === null && !empty($grupo_counts)) {
        $grupo = array_key_first($grupo_counts);
    }

    return $grupo ?? '';
}

function calcularPeriodo($id_matricula)
{
    $matricula = ModelMatricula::query()
        ->with('matriculaGestion', 'admitido')
        ->where('id_matricula', $id_matricula)
        ->first();

    if (!$matricula) {
        return '';
    }

    $admision = $matricula->admitido->programa_proceso->admision;
    $anio = $admision->admision_año;
    $periodo = $matricula->admitido->matriculas()->count();

    return $anio . ' - ' . $periodo;
}

function cantidadAlumnosMatriculadosCurso($id_curso_programa_plan, $id_programa_proceso_grupo)
{
    return ModelMatriculaCurso::query()
        ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
        ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
        ->join('persona', 'admitido.id_persona', 'persona.id_persona')
        ->where('admitido.admitido_estado', 1)
        ->where('tbl_matricula_curso.id_curso_programa_plan', $id_curso_programa_plan)
        ->where('tbl_matricula_curso.id_programa_proceso_grupo', $id_programa_proceso_grupo)
        ->where('tbl_matricula_curso.activo', 1)
        ->count();
}

function cantidadAlumnosMatriculadosCursoFinalizado($id_curso_programa_plan, $id_programa_proceso_grupo)
{
    return ModelMatriculaCurso::query()
        ->join('tbl_matricula', 'tbl_matricula_curso.id_matricula', 'tbl_matricula.id_matricula')
        ->join('admitido', 'tbl_matricula.id_admitido', 'admitido.id_admitido')
        ->join('persona', 'admitido.id_persona', 'persona.id_persona')
        ->where('admitido.admitido_estado', 1)
        ->where('tbl_matricula_curso.id_curso_programa_plan', $id_curso_programa_plan)
        ->where('tbl_matricula_curso.id_programa_proceso_grupo', $id_programa_proceso_grupo)
        ->where('tbl_matricula_curso.estado', '!=', 1)
        ->where('tbl_matricula_curso.activo', 1)
        ->count();
}

function calcularPromedio($nota1, $nota2, $nota3)
{
    // nota1 = 14, nota2 = 13, nota3 = 14 => 13.67
    // redondear a entero => 14 (aprobado)
    // si es menor a 13.45 => 13 (reprobado)
    $promedio = ($nota1 + $nota2 + $nota3) / 3;
    $promedio = round($promedio, 2);
    if ($promedio < 13.50) {
        $promedio = round($promedio, 0, PHP_ROUND_HALF_DOWN); // redondear hacia abajo
    } else {
        $promedio = round($promedio, 0, PHP_ROUND_HALF_UP); // redondear hacia arriba
    }
    return $promedio;
}

function calcularCreditosAcumulados($id_admitido)
{
    $matriculaCursos = ModelMatriculaCurso::query()
        ->with([
            'matricula' => function ($query) use ($id_admitido) {
                $query->where('id_admitido', $id_admitido);
            },
            'cursoProgramaPlan' => function ($query) {
                $query->with('curso');
            }
        ])
        ->where('estado', 2) // 1 = Pendiente, 2 = Aprobado, 3 = Nsp
        ->whereHas('matricula', function ($query) use ($id_admitido) {
            $query->where('id_admitido', $id_admitido);
        })
        ->get();

    $creditos_acumulados = 0;

    foreach ($matriculaCursos as $matriculaCurso) {
        $creditos_acumulados += $matriculaCurso->cursoProgramaPlan->curso->curso_credito;
    }

    $admitido = Admitido::query()
        ->find($id_admitido);

    $admitido->creditos_acumulados = $creditos_acumulados;
    $admitido->save();
}

function verificarTieneReingreso($id_admitido)
{
    $tieneReingreso = Reingreso::query()
        ->where('id_admitido', $id_admitido)
        ->where('reingreso_estado', 1)
        ->exists();

    return $tieneReingreso;
}

function calcularCA($admitido, $ciclo)
{
    $ca = 0;

    $matriculaCursos = ModelMatriculaCurso::query()
        ->with([
            'matricula' => function ($query) use ($admitido) {
                $query->where('id_admitido', $admitido->id_admitido);
            },
            'cursoProgramaPlan' => function ($query) use ($ciclo) {
                $query->with([
                    'curso' => function ($query) use ($ciclo) {
                        $query->where('id_ciclo', $ciclo->id_ciclo);
                    }
                ]);
            }
        ])
        ->where(function ($query) {
            $query->where('estado', 2)
                ->orWhere('estado', 0);
        }) // 1 = Pendiente, 2 = Aprobado, 3 = Nsp, 0 = No aprobado
        ->whereHas('matricula', function ($query) use ($admitido) {
            $query->where('id_admitido', $admitido->id_admitido);
        })
        ->whereHas('cursoProgramaPlan', function ($query) use ($ciclo) {
            $query->whereHas('curso', function ($query) use ($ciclo) {
                $query->where('id_ciclo', $ciclo->id_ciclo);
            });
        })
        ->get();

    $cursos = [];
    foreach ($matriculaCursos as $matriculaCurso) {
        if (!in_array($matriculaCurso->cursoProgramaPlan->id_curso_programa_plan, $cursos)) {
            $cursos[] = $matriculaCurso->cursoProgramaPlan->id_curso_programa_plan;
            $ca += $matriculaCurso->cursoProgramaPlan->curso->curso_credito;
        }
    }

    return $ca;
}

function calcularCAA($admitido, $ciclo)
{
    $caa = 0;

    $matriculaCursos = ModelMatriculaCurso::query()
        ->with([
            'matricula' => function ($query) use ($admitido) {
                $query->where('id_admitido', $admitido->id_admitido);
            },
            'cursoProgramaPlan' => function ($query) use ($ciclo) {
                $query->with([
                    'curso' => function ($query) use ($ciclo) {
                        $query->where('id_ciclo', '<=', $ciclo->id_ciclo);
                    }
                ]);
            }
        ])
        // ->where('estado', 2) // 1 = Pendiente, 2 = Aprobado, 3 = Nsp, 0 = No aprobado
        ->where(function ($query) {
            $query->where('estado', 2)
                ->orWhere('estado', 0);
        })
        ->whereHas('matricula', function ($query) use ($admitido) {
            $query->where('id_admitido', $admitido->id_admitido);
        })
        ->whereHas('cursoProgramaPlan', function ($query) use ($ciclo) {
            $query->whereHas('curso', function ($query) use ($ciclo) {
                $query->where('id_ciclo', '<=', $ciclo->id_ciclo);
            });
        })
        ->get();

    $cursos = [];
    foreach ($matriculaCursos as $matriculaCurso) {
        if (!in_array($matriculaCurso->cursoProgramaPlan->id_curso_programa_plan, $cursos)) {
            $cursos[] = $matriculaCurso->cursoProgramaPlan->id_curso_programa_plan;
            $caa += $matriculaCurso->cursoProgramaPlan->curso->curso_credito;
        }
    }

    return $caa;
}

function calcularPPA($admitido, $ciclo)
{
    $ppa = 0;
    $ca = calcularCA($admitido, $ciclo);

    $matriculaCursos = ModelMatriculaCurso::query()
        ->with([
            'matricula' => function ($query) use ($admitido) {
                $query->where('id_admitido', $admitido->id_admitido);
            },
            'cursoProgramaPlan' => function ($query) use ($ciclo) {
                $query->with([
                    'curso' => function ($query) use ($ciclo) {
                        $query->where('id_ciclo', $ciclo->id_ciclo);
                    }
                ]);
            }
        ])
        ->where(function ($query) {
            $query->where('estado', 2)
                ->orWhere('estado', 0);
        }) // 1 = Pendiente, 2 = Aprobado, 3 = Nsp, 0 = No aprobado
        ->whereHas('matricula', function ($query) use ($admitido) {
            $query->where('id_admitido', $admitido->id_admitido);
        })
        ->whereHas('cursoProgramaPlan', function ($query) use ($ciclo) {
            $query->whereHas('curso', function ($query) use ($ciclo) {
                $query->where('id_ciclo', $ciclo->id_ciclo);
            });
        })
        ->orderBy('fecha_ingreso_nota', 'desc')
        ->get();

    $cursos = [];
    foreach ($matriculaCursos as $matriculaCurso) {
        if (!in_array($matriculaCurso->cursoProgramaPlan->id_curso_programa_plan, $cursos)) {
            $cursos[] = $matriculaCurso->cursoProgramaPlan->id_curso_programa_plan;
            $ppa += $matriculaCurso->cursoProgramaPlan->curso->curso_credito * $matriculaCurso->nota_promedio_final;
        }
    }

    if ($ca == 0) {
        return 0;
    }

    $ppa = $ppa / $ca;
    $ppa = round($ppa, 2);

    return $ppa;
}

function calcularPPS($admitido, $ciclo)
{
    $pps = 0;
    $caa = calcularCAA($admitido, $ciclo);

    $matriculaCursos = ModelMatriculaCurso::query()
        ->with([
            'matricula' => function ($query) use ($admitido) {
                $query->where('id_admitido', $admitido->id_admitido);
            },
            'cursoProgramaPlan' => function ($query) use ($ciclo) {
                $query->with([
                    'curso' => function ($query) use ($ciclo) {
                        $query->where('id_ciclo', '<=', $ciclo->id_ciclo);
                    }
                ]);
            }
        ])
        ->where(function ($query) {
            $query->where('estado', 2)
                ->orWhere('estado', 0);
        }) // 1 = Pendiente, 2 = Aprobado, 3 = Nsp, 0 = No aprobado
        ->whereHas('matricula', function ($query) use ($admitido) {
            $query->where('id_admitido', $admitido->id_admitido);
        })
        ->whereHas('cursoProgramaPlan', function ($query) use ($ciclo) {
            $query->whereHas('curso', function ($query) use ($ciclo) {
                $query->where('id_ciclo', '<=', $ciclo->id_ciclo);
            });
        })
        ->orderBy('fecha_ingreso_nota', 'desc')
        ->get();

    $cursos = [];
    foreach ($matriculaCursos as $matriculaCurso) {
        if (!in_array($matriculaCurso->cursoProgramaPlan->id_curso_programa_plan, $cursos)) {
            $cursos[] = $matriculaCurso->cursoProgramaPlan->id_curso_programa_plan;
            $pps += $matriculaCurso->cursoProgramaPlan->curso->curso_credito * $matriculaCurso->nota_promedio_final;
        }
    }

    if ($ciclo->id_ciclo == 2) {
        // dd($pps);
    }

    if ($caa == 0) {
        return 0;
    }

    $pps = $pps / $caa;
    $pps = round($pps, 2);

    return $pps;
}

function getResolucionReingreso($id_admitido)
{
    $reingreso = Reingreso::query()
        ->where('id_admitido', $id_admitido)
        ->where('reingreso_estado', 1)
        ->first();

    if (!$reingreso) {
        return '';
    }

    return $reingreso->reingreso_resolucion;
}

function getResolucionIncorporacion($id_admitido)
{
    $reingreso = Reincorporacion::query()
        ->where('id_admitido', $id_admitido)
        ->where('reincorporacion_estado', 1)
        ->first();

    if (!$reingreso) {
        return '';
    }

    return $reingreso->reincorporacion_resolucion;
}

function calcularCantidadVecesLlevaCurso($id_admitido, $id_curso_programa_plan)
{
    $cantidad = ModelMatriculaCurso::query()
        ->with([
            'matricula' => function ($query) use ($id_admitido) {
                $query->where('id_admitido', $id_admitido);
            }
        ])
        ->where('id_curso_programa_plan', $id_curso_programa_plan)
        ->whereHas('matricula', function ($query) use ($id_admitido) {
            $query->where('id_admitido', $id_admitido);
        })
        ->count();

    return $cantidad + 1;
}

function getNombreResolucionReingreso($id_matricula_curso)
{
    $matriculaCurso = ModelMatriculaCurso::find($id_matricula_curso);

    if (!$matriculaCurso) {
        return '';
    }

    if($matriculaCurso->id_reingreso) {
        return $matriculaCurso->reingreso->reingreso_resolucion ?? '';
    }

    return '';
}

//
