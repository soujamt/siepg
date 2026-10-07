<?php

declare(strict_types=1);

/**
 * Crea el proceso de admision 2026 - II con los 23 programas en modalidad a distancia (VIRTUAL)
 * usando el mismo plan de estudios del proceso anterior (P2025).
 *
 * Que hace:
 *   1. Activa la modalidad VIRTUAL (id 2) para que aparezca en el formulario de inscripcion.
 *   2. Crea el proceso "ADMISION 2026 - 2" (se muestra como 2026 - II) con las fechas del cronograma.
 *   3. Por cada programa a distancia crea su programa_plan con P2025 (codigo ej. DADV25), dejando
 *      inactivo el plan anterior (P2010/P2022) igual que lo hace la vista de Gestion de Plan y Proceso.
 *   4. Copia los cursos del plan P2025 desde su programa presencial equivalente.
 *   5. Crea el programa_proceso de cada programa para el nuevo proceso y registra su link de WhatsApp.
 *   6. Copia los expedientes requeridos del proceso 2026 - 1.
 *   7. Con --activar deja el nuevo proceso como activo (desactiva el actual, igual que la vista de admision).
 *
 * No modifica inscripciones, admitidos, matriculas ni los programa_proceso de procesos anteriores.
 * Es idempotente: si se vuelve a ejecutar solo crea lo que falte.
 *
 * Uso:
 *   php scripts/crear_proceso_2026_II_distancia.php
 *   php scripts/crear_proceso_2026_II_distancia.php --apply
 *   php scripts/crear_proceso_2026_II_distancia.php --apply --activar
 *
 * Sin --apply corre en modo simulacion y revierte la transaccion.
 */

$apply = in_array('--apply', $argv, true);
$activar = in_array('--activar', $argv, true);

$config = [
    'host' => env_value('DB_HOST', 'localhost'),
    'port' => env_value('DB_PORT', '3306'),
    'database' => env_value('DB_DATABASE', 'siepg'),
    'username' => env_value('DB_USERNAME', 'root'),
    'password' => env_value('DB_PASSWORD', 'root'),
];

$pdo = new PDO(
    sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['database']
    ),
    $config['username'],
    $config['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$admisionAnio = 2026;
$admisionConvocatoria = 2;
$admisionNombre = "ADMISION {$admisionAnio} - {$admisionConvocatoria}"; // mismo formato que la vista de admision
$idModalidadDistancia = 2;
$idModalidadPresencial = 1;
$planCodigo = 'P2025';
$cantidadProgramasEsperados = 23;
$admisionReferenciaNombre = 'ADMISION 2026 - 1'; // de aqui se copian los expedientes requeridos

// Cronograma de admision proceso 2026 - II modalidad a distancia
$fechas = [
    'admision_fecha_inicio_inscripcion' => '2026-10-07',
    'admision_fecha_fin_inscripcion' => '2026-11-09',
    'admision_fecha_inicio_expediente' => '2026-11-10',
    'admision_fecha_fin_expediente' => '2026-11-11',
    'admision_fecha_inicio_entrevista' => '2026-11-12',
    'admision_fecha_fin_entrevista' => '2026-11-13',
    'admision_fecha_resultados' => '2026-11-16',
    'admision_fecha_inicio_matricula' => '2026-11-17',
    'admision_fecha_fin_matricula' => '2026-11-20',
    'admision_fecha_inicio_matricula_extemporanea' => '2026-11-23',
    'admision_fecha_fin_matricula_extemporanea' => '2026-11-24',
];

// Links de los grupos de WhatsApp por programa (programa_iniciales => link), documento "LINK 2026 2.docx"
$linksWhatsapp = [
    // Maestrias
    'MSP' => 'https://chat.whatsapp.com/GatmH4cFCVALmNuCLW4Kmc', // Salud Publica
    'MDC' => 'https://chat.whatsapp.com/Gt1Feea4gUjID2cVFH8zLD', // Derecho Constitucional y Administrativo
    'MEA' => 'https://chat.whatsapp.com/JqVpFiSiEuV11ruuR18yh6', // Evaluacion y Acreditacion de la Calidad de la Educacion
    'MPE' => 'https://chat.whatsapp.com/G1sLwlnrrbg6y9nDhzl3rH', // Educacion - Psicologia Educativa
    'MGE' => 'https://chat.whatsapp.com/G3moPPwKLqZ8GKbbeb943D', // Educacion - Gestion Educativa
    'MPS' => 'https://chat.whatsapp.com/BZuOYRHfg2rAFpMvPAJQH7', // Educacion - Psicopedagogia
    'MDL' => 'https://chat.whatsapp.com/F7z1uxufYJx80eUnG2Ueh5', // Educacion - Didactica de la Literatura
    'MEI' => 'https://chat.whatsapp.com/Khk5mDpoj0bEngyhT4NB5z', // Educacion - Educacion Infantil
    'MDP' => 'https://chat.whatsapp.com/J9mNu0Adf911WFaplRZQai', // Educacion - Docencia y Pedagogia Universitaria
    'MMA' => 'https://chat.whatsapp.com/JLTUuI04whgAuEOX1Xpq7P', // Medio Ambiente, Desarrollo Sostenible y Responsabilidad Social
    'MAS' => 'https://chat.whatsapp.com/CqArx8hbqi41eHylUquXvP', // Ciencias Agricolas - Agricultura Sostenible
    'MTI' => 'https://chat.whatsapp.com/IS5HgANZBP3HdL9Q3PzKG2', // Ingenieria de Sistemas - Gestion de Tecnologias de la Informacion
    'MGP' => 'https://chat.whatsapp.com/GJFVrUlStHS1kmYRTUoRzd', // Gestion Publica
    'MNI' => 'https://chat.whatsapp.com/JcClnr0cNAlI7Px4DcPZEK', // Gestion Empresarial - Negocios Internacionales y Comercio Exterior
    'MRC' => 'https://chat.whatsapp.com/FQGcYaoNjkhAoAdGMZfWNT', // Gestion Empresarial - Recursos y Costos de Agronegocios
    'MFE' => 'https://chat.whatsapp.com/GmMKL3stpln8M31UI1ldcn', // Gestion Empresarial - Finanzas para Empresas Financieras
    'MPI' => 'https://chat.whatsapp.com/DqhinHz74qV5JhYJa0sk5g', // Gestion Empresarial - Proyectos de Inversion
    'MTF' => 'https://chat.whatsapp.com/HlfnG2TBc64JxqTEFDGaAx', // Gestion Empresarial - Gestion Tributaria y Fiscal
    'MAG' => 'https://chat.whatsapp.com/GkpRi6HPn4l5a2X7fTfYYB', // Gestion Empresarial - Auditoria de la Gestion Empresarial
    // MCP (Ciencias de la Computacion): no tendra grupo de WhatsApp
    // Doctorados
    'DSP' => 'https://chat.whatsapp.com/ImZimooo0fcA65g7lv1KN3', // Salud Publica
    'DED' => 'https://chat.whatsapp.com/DEcVcbDRrdXIFL3aao5OTE', // Educacion
    'DAD' => 'https://chat.whatsapp.com/IYTCWyJizB559cVhSqsViD', // Administracion
];

$ahora = date('Y-m-d H:i:s');

echo $apply ? "MODO APLICACION\n" : "MODO SIMULACION - no se guardaran cambios\n";
echo "Base de datos: {$config['database']}\n";

$pdo->beginTransaction();

try {
    // ------------------------------------------------------------------
    // Validaciones previas
    // ------------------------------------------------------------------
    $modalidad = one($pdo, 'SELECT * FROM modalidad WHERE id_modalidad = ?', [$idModalidadDistancia]);
    if (!$modalidad) {
        fail("No existe la modalidad {$idModalidadDistancia}.");
    }

    $plan = one($pdo, 'SELECT * FROM plan WHERE plan_codigo = ? AND plan_estado = 1', [$planCodigo]);
    if (!$plan) {
        fail("No existe el plan {$planCodigo} activo.");
    }

    $admisionReferencia = one(
        $pdo,
        'SELECT * FROM admision WHERE admision = ? AND deleted_at IS NULL',
        [$admisionReferenciaNombre]
    );
    if (!$admisionReferencia) {
        fail("No existe el proceso de referencia {$admisionReferenciaNombre}.");
    }

    $programas = all(
        $pdo,
        'SELECT * FROM programa WHERE id_modalidad = ? AND programa_estado = 1 ORDER BY id_programa',
        [$idModalidadDistancia]
    );
    if (count($programas) !== $cantidadProgramasEsperados) {
        fail('Se esperaban ' . $cantidadProgramasEsperados . ' programas a distancia activos y hay ' . count($programas) . '.');
    }

    // Emparejamos cada programa a distancia con su programa presencial del plan P2025
    $pares = [];
    foreach ($programas as $programa) {
        $equivalentes = all(
            $pdo,
            'SELECT p.*, pp.id_programa_plan AS id_programa_plan_presencial
               FROM programa p
               JOIN programa_plan pp ON pp.id_programa = p.id_programa AND pp.id_plan = ?
              WHERE p.id_modalidad = ?
                AND p.programa_estado = 1
                AND p.programa_iniciales = ?
                AND p.programa_tipo = ?',
            [$plan['id_plan'], $idModalidadPresencial, $programa['programa_iniciales'], $programa['programa_tipo']]
        );

        if (count($equivalentes) !== 1) {
            fail("El programa {$programa['id_programa']} ({$programa['programa_iniciales']}) no tiene un unico programa presencial equivalente con {$planCodigo}.");
        }

        $equivalente = $equivalentes[0];
        if (nombre_programa($equivalente) !== nombre_programa($programa)) {
            fail("El programa {$programa['id_programa']} no coincide con su equivalente presencial {$equivalente['id_programa']}.");
        }

        $pares[] = [$programa, $equivalente];
    }
    log_step('Validaciones previas correctas: ' . count($pares) . " programas a distancia emparejados con su plan {$planCodigo} presencial.");

    // ------------------------------------------------------------------
    // 1. Modalidad a distancia activa
    // ------------------------------------------------------------------
    log_step("Modalidad {$modalidad['modalidad']} (id {$idModalidadDistancia})");
    if ((int) $modalidad['modalidad_estado'] !== 1) {
        exec_sql($pdo, 'UPDATE modalidad SET modalidad_estado = 1 WHERE id_modalidad = ?', [$idModalidadDistancia]);
    } else {
        echo "  ya se encuentra activa\n";
    }

    // ------------------------------------------------------------------
    // 2. Proceso de admision
    // ------------------------------------------------------------------
    log_step("Proceso de admision {$admisionNombre}");
    $admision = one(
        $pdo,
        'SELECT * FROM admision WHERE `admision_año` = ? AND admision_convocatoria = ? AND deleted_at IS NULL',
        [$admisionAnio, $admisionConvocatoria]
    );

    if ($admision) {
        echo "  ya existe con id {$admision['id_admision']}, no se modifican sus datos\n";
        foreach ($fechas as $columna => $valor) {
            if ($admision[$columna] !== $valor) {
                echo "  AVISO: {$columna} es {$admision[$columna]} y el cronograma indica {$valor}\n";
            }
        }
    } else {
        $columnas = array_merge(['admision', '`admision_año`', 'admision_convocatoria', 'admision_estado'], array_keys($fechas));
        $valores = array_merge([$admisionNombre, $admisionAnio, $admisionConvocatoria, 0], array_values($fechas));
        exec_sql(
            $pdo,
            'INSERT INTO admision (' . implode(', ', $columnas) . ') VALUES (' . implode(', ', array_fill(0, count($valores), '?')) . ')',
            $valores
        );
        $admision = one($pdo, 'SELECT * FROM admision WHERE id_admision = ?', [(int) $pdo->lastInsertId()]);
        echo "  creado con id {$admision['id_admision']}\n";
    }
    $idAdmision = (int) $admision['id_admision'];

    // ------------------------------------------------------------------
    // 3, 4 y 5. Plan, cursos y proceso de cada programa
    // ------------------------------------------------------------------
    $letraModalidad = strtoupper(substr($modalidad['modalidad'], 0, 1)); // misma regla que GestionPlanProceso
    $sufijoPlan = substr((string) $plan['plan'], -2);

    foreach ($pares as [$programa, $equivalente]) {
        $codigo = $programa['programa_iniciales'] . $letraModalidad . $sufijoPlan;
        log_step("Programa {$programa['id_programa']} " . nombre_programa($programa) . " -> {$codigo}");

        // programa_plan con P2025
        $programaPlan = one(
            $pdo,
            'SELECT * FROM programa_plan WHERE id_programa = ? AND id_plan = ?',
            [$programa['id_programa'], $plan['id_plan']]
        );

        if ($programaPlan) {
            echo "  programa_plan ya existe (id {$programaPlan['id_programa_plan']}, {$programaPlan['programa_codigo']})\n";
            if ((int) $programaPlan['programa_plan_estado'] !== 1) {
                echo "  se activa el programa_plan\n";
                exec_sql($pdo, 'UPDATE programa_plan SET programa_plan_estado = 1 WHERE id_programa_plan = ?', [$programaPlan['id_programa_plan']]);
            }
        } else {
            $codigoRepetido = one(
                $pdo,
                'SELECT id_programa_plan FROM programa_plan WHERE programa_codigo = ?',
                [$codigo]
            );
            if ($codigoRepetido) {
                fail("El codigo {$codigo} ya esta usado por el programa_plan {$codigoRepetido['id_programa_plan']}.");
            }

            exec_sql(
                $pdo,
                'INSERT INTO programa_plan (programa_codigo, id_programa, id_plan, programa_plan_creacion, programa_plan_estado)
                 VALUES (?, ?, ?, ?, 1)',
                [$codigo, $programa['id_programa'], $plan['id_plan'], $ahora]
            );
            $programaPlan = one($pdo, 'SELECT * FROM programa_plan WHERE id_programa_plan = ?', [(int) $pdo->lastInsertId()]);
            echo "  programa_plan creado (id {$programaPlan['id_programa_plan']})\n";
        }
        $idProgramaPlan = (int) $programaPlan['id_programa_plan'];

        // el plan anterior queda inactivo (solo afecta al listado de programas del formulario de inscripcion)
        $planesAnteriores = all(
            $pdo,
            'SELECT id_programa_plan, programa_codigo FROM programa_plan
              WHERE id_programa = ? AND id_programa_plan <> ? AND programa_plan_estado = 1',
            [$programa['id_programa'], $idProgramaPlan]
        );
        foreach ($planesAnteriores as $planAnterior) {
            echo "  se desactiva el plan anterior {$planAnterior['programa_codigo']} (id {$planAnterior['id_programa_plan']})\n";
            exec_sql($pdo, 'UPDATE programa_plan SET programa_plan_estado = 0 WHERE id_programa_plan = ?', [$planAnterior['id_programa_plan']]);
        }

        // cursos del plan P2025 copiados desde el programa presencial
        $cursos = all(
            $pdo,
            'SELECT id_curso, curso_programa_plan_estado FROM curso_programa_plan WHERE id_programa_plan = ? ORDER BY id_curso_programa_plan',
            [$equivalente['id_programa_plan_presencial']]
        );
        $cursosCreados = 0;
        foreach ($cursos as $curso) {
            $existe = one(
                $pdo,
                'SELECT id_curso_programa_plan FROM curso_programa_plan WHERE id_programa_plan = ? AND id_curso = ?',
                [$idProgramaPlan, $curso['id_curso']]
            );
            if ($existe) {
                continue;
            }
            $statement = $pdo->prepare(
                'INSERT INTO curso_programa_plan (id_curso, id_programa_plan, curso_programa_plan_fecha_creacion, curso_programa_plan_estado)
                 VALUES (?, ?, ?, ?)'
            );
            $statement->execute([$curso['id_curso'], $idProgramaPlan, $ahora, $curso['curso_programa_plan_estado']]);
            $cursosCreados++;
        }
        echo '  cursos: ' . count($cursos) . " en el plan presencial, {$cursosCreados} copiados\n";
        if (count($cursos) === 0) {
            echo "  AVISO: el plan presencial no tiene cursos, se deben registrar antes de la matricula\n";
        }

        // programa_proceso del nuevo proceso
        $programaProceso = one(
            $pdo,
            'SELECT * FROM programa_proceso WHERE id_admision = ? AND id_programa_plan = ?',
            [$idAdmision, $idProgramaPlan]
        );
        if ($programaProceso) {
            $idProgramaProceso = (int) $programaProceso['id_programa_proceso'];
            echo "  programa_proceso ya existe (id {$idProgramaProceso})\n";
        } else {
            exec_sql(
                $pdo,
                'INSERT INTO programa_proceso (id_admision, id_programa_plan, programa_proceso_estado) VALUES (?, ?, 1)',
                [$idAdmision, $idProgramaPlan]
            );
            $idProgramaProceso = (int) $pdo->lastInsertId();
            echo "  programa_proceso creado (id {$idProgramaProceso})\n";
        }

        // link del grupo de WhatsApp (mismos campos que la vista de Links WhatsApp)
        $linkWhatsapp = $linksWhatsapp[$programa['programa_iniciales']] ?? null;
        $linkRegistrado = one(
            $pdo,
            'SELECT * FROM link_whatsapp_programas WHERE id_programa_proceso = ? AND id_admision = ?',
            [$idProgramaProceso, $idAdmision]
        );
        if ($linkRegistrado) {
            echo "  link WhatsApp ya registrado: {$linkRegistrado['link_whatsapp']}\n";
            if ($linkWhatsapp !== null && $linkRegistrado['link_whatsapp'] !== $linkWhatsapp) {
                echo "  AVISO: el link registrado es distinto al del documento ({$linkWhatsapp}), no se modifica\n";
            }
        } elseif ($linkWhatsapp === null) {
            echo "  AVISO: sin link de WhatsApp, registrarlo en Gestion Admision > Links WhatsApp\n";
        } else {
            $linkUsado = one($pdo, 'SELECT id_programa_proceso FROM link_whatsapp_programas WHERE link_whatsapp = ?', [$linkWhatsapp]);
            if ($linkUsado) {
                fail("El link {$linkWhatsapp} ya esta registrado en el programa_proceso {$linkUsado['id_programa_proceso']}.");
            }
            exec_sql(
                $pdo,
                'INSERT INTO link_whatsapp_programas (link_whatsapp, id_programa_proceso, id_admision, link_whatsapp_fecha_creacion, link_whatsapp_estado)
                 VALUES (?, ?, ?, ?, 1)',
                [$linkWhatsapp, $idProgramaProceso, $idAdmision, $ahora]
            );
            echo "  link WhatsApp registrado\n";
        }
    }

    // ------------------------------------------------------------------
    // 6. Expedientes requeridos
    // ------------------------------------------------------------------
    log_step("Expedientes requeridos (copiados de {$admisionReferenciaNombre})");
    $expedientes = all(
        $pdo,
        'SELECT ea.id_expediente, e.expediente
           FROM expediente_admision ea
           JOIN expediente e ON e.id_expediente = ea.id_expediente
          WHERE ea.id_admision = ? AND ea.expediente_admision_estado = 1
          ORDER BY ea.id_expediente_admision',
        [$admisionReferencia['id_admision']]
    );
    foreach ($expedientes as $expediente) {
        $existe = one(
            $pdo,
            'SELECT id_expediente_admision FROM expediente_admision WHERE id_admision = ? AND id_expediente = ?',
            [$idAdmision, $expediente['id_expediente']]
        );
        if ($existe) {
            echo "  ya existe: {$expediente['expediente']}\n";
            continue;
        }
        echo "  {$expediente['expediente']}\n";
        exec_sql(
            $pdo,
            'INSERT INTO expediente_admision (id_expediente, id_admision, expediente_admision_estado) VALUES (?, ?, 1)',
            [$expediente['id_expediente'], $idAdmision]
        );
    }

    // ------------------------------------------------------------------
    // 7. Activacion del proceso (opcional)
    // ------------------------------------------------------------------
    if ($activar) {
        log_step("Activando {$admisionNombre} (se desactiva el proceso activo actual)");
        exec_sql($pdo, 'UPDATE admision SET admision_estado = 0 WHERE admision_estado = 1 AND id_admision <> ?', [$idAdmision]);
        exec_sql($pdo, 'UPDATE admision SET admision_estado = 1 WHERE id_admision = ?', [$idAdmision]);
    } else {
        log_step('El proceso no se activa. Usa --activar o el boton de estado en Gestion de Admision cuando corresponda.');
    }

    // ------------------------------------------------------------------
    // Resumen
    // ------------------------------------------------------------------
    log_step('Resumen del proceso');
    print_rows(
        $pdo,
        'SELECT a.id_admision, a.admision, a.admision_estado,
                COUNT(DISTINCT pp.id_programa_proceso) AS programas,
                SUM(p.programa_tipo = 1) AS maestrias,
                SUM(p.programa_tipo = 2) AS doctorados,
                (SELECT COUNT(*) FROM expediente_admision ea WHERE ea.id_admision = a.id_admision) AS expedientes,
                (SELECT COUNT(*) FROM link_whatsapp_programas lw WHERE lw.id_admision = a.id_admision) AS links_whatsapp
           FROM admision a
           LEFT JOIN programa_proceso pp ON pp.id_admision = a.id_admision
           LEFT JOIN programa_plan pl ON pl.id_programa_plan = pp.id_programa_plan
           LEFT JOIN programa p ON p.id_programa = pl.id_programa
          WHERE a.id_admision = ?
          GROUP BY a.id_admision',
        [$idAdmision]
    );

    if ($apply) {
        $pdo->commit();
        echo "\nCambios aplicados correctamente.\n";
    } else {
        $pdo->rollBack();
        echo "\nSimulacion completada. Ejecuta con --apply para guardar.\n";
    }
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    fwrite(STDERR, "\nERROR: {$exception->getMessage()}\n");
    exit(1);
}

function env_value(string $key, string $default): string
{
    static $env = null;

    if ($env === null) {
        $env = [];
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';

        if (is_file($path)) {
            foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $env[trim($name)] = trim($value, " \t\n\r\0\x0B\"'");
            }
        }
    }

    return $env[$key] ?? $default;
}

function one(PDO $pdo, string $sql, array $params = []): ?array
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    $row = $statement->fetch();

    return $row ?: null;
}

function all(PDO $pdo, string $sql, array $params = []): array
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    return $statement->fetchAll();
}

function exec_sql(PDO $pdo, string $sql, array $params = []): int
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    echo '  filas afectadas: ' . $statement->rowCount() . "\n";

    return $statement->rowCount();
}

function nombre_programa(array $programa): string
{
    return trim($programa['programa'] . ' ' . $programa['subprograma'] . ' ' . ($programa['mencion'] ?? ''));
}

function log_step(string $message): void
{
    echo "\n- {$message}\n";
}

function fail(string $message): void
{
    throw new RuntimeException($message);
}

function print_rows(PDO $pdo, string $sql, array $params = []): void
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    foreach ($statement->fetchAll() as $row) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
