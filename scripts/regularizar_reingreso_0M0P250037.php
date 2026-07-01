<?php

declare(strict_types=1);

/**
 * Regulariza la matricula del admitido 0M0P250037 reingresado a 2026-1.
 *
 * Uso:
 *   php scripts/regularizar_reingreso_0M0P250037.php
 *   php scripts/regularizar_reingreso_0M0P250037.php --apply
 *
 * Sin --apply corre en modo simulacion y revierte la transaccion.
 */

$apply = in_array('--apply', $argv, true);

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

$codigoAdmitido = '0M0P250037';
$idProgramaProcesoActual = 309;
$idProgramaProcesoAnterior = 193;
$idGrupo2026 = 31;
$idGrupo2025 = 21;
$idReingreso = 51;
$idMatriculaHistorica = 1594;
$idMatriculaCero = 2900;
$idCursoAprobado = 284;
$cursosPendientes = [285, 286, 287];
$periodoRegularizacion = '2026 - 1';
$fechaRegularizacion = date('Y-m-d');
$pagoOperacion = '2026-D14138';
$pagoMonto = '200.00';
$pagoFecha = '2026-05-22';
$idCanalPagoCaja = 4;
$idConceptoMatriculaExtemporanea = 5;
$voucherOrigen = '/tmp/voucher-pago-2026-D14138.jpg';
$voucherDestinoRelativo = 'Posgrado/ADMISION 2026 - 1/40795500/Voucher/voucher-pago-2026-D14138.jpg';

echo $apply ? "MODO APLICACION\n" : "MODO SIMULACION - no se guardaran cambios\n";

$pdo->beginTransaction();

try {
    $admitido = one(
        $pdo,
        'SELECT a.*, p.numero_documento, p.apellido_paterno, p.apellido_materno, p.nombre
           FROM admitido a
           JOIN persona p ON p.id_persona = a.id_persona
          WHERE a.admitido_codigo = ?',
        [$codigoAdmitido]
    );

    if (!$admitido) {
        fail("No existe el admitido {$codigoAdmitido}.");
    }

    if ((int) $admitido['id_admitido'] !== 902) {
        fail("El admitido {$codigoAdmitido} no tiene el id esperado 902.");
    }

    $reingreso = one(
        $pdo,
        'SELECT * FROM reingreso WHERE id_reingreso = ? AND id_admitido = ? AND reingreso_estado = 1',
        [$idReingreso, $admitido['id_admitido']]
    );

    if (!$reingreso) {
        fail("No existe el reingreso activo {$idReingreso} para el admitido.");
    }

    $grupo = one(
        $pdo,
        'SELECT * FROM programa_proceso_grupo
          WHERE id_programa_proceso_grupo = ?
            AND id_programa_proceso = ?
            AND programa_proceso_grupo_estado = 1',
        [$idGrupo2026, $idProgramaProcesoActual]
    );

    if (!$grupo) {
        fail("No existe el grupo activo {$idGrupo2026} para el proceso {$idProgramaProcesoActual}.");
    }

    $matriculaCero = one(
        $pdo,
        'SELECT * FROM tbl_matricula WHERE id_matricula = ? AND id_admitido = ?',
        [$idMatriculaCero, $admitido['id_admitido']]
    );

    if (!$matriculaCero) {
        fail("No existe la matricula cero esperada {$idMatriculaCero}.");
    }

    log_step('Normalizando admitido como reingresante 2026-1');
    exec_sql(
        $pdo,
        'UPDATE admitido
            SET id_programa_proceso = ?,
                id_programa_proceso_antiguo = ?,
                admitido_estado = 1,
                ingresante = 0,
                creditos_acumulados = 4
          WHERE id_admitido = ?',
        [$idProgramaProcesoActual, $idProgramaProcesoAnterior, $admitido['id_admitido']]
    );

    log_step('Corrigiendo curso aprobado de la matricula cero al grupo 2026-1');
    $cursoAprobado = one(
        $pdo,
        'SELECT tmc.*
           FROM tbl_matricula_curso tmc
          WHERE tmc.id_matricula = ?
            AND tmc.id_curso_programa_plan = ?',
        [$idMatriculaCero, $idCursoAprobado]
    );

    if (!$cursoAprobado) {
        fail("La matricula cero {$idMatriculaCero} no tiene el curso aprobado {$idCursoAprobado}.");
    }

    exec_sql(
        $pdo,
        'UPDATE tbl_matricula_curso
            SET id_programa_proceso_grupo = ?,
                id_reingreso = ?,
                es_acta_reingreso = 1,
                estado = 2,
                activo = 0,
                nota_promedio_final = 14.00,
                fecha_ingreso_nota = COALESCE(fecha_ingreso_nota, ?)
          WHERE id_matricula_curso = ?',
        [$idGrupo2026, $idReingreso, $fechaRegularizacion, $cursoAprobado['id_matricula_curso']]
    );

    log_step('Desactivando cursos vigentes de la matricula 2025 para evitar doble vigencia');
    exec_sql(
        $pdo,
        'UPDATE tbl_matricula_curso
            SET activo = 0
          WHERE id_matricula = ?
            AND id_programa_proceso_grupo = ?',
        [$idMatriculaHistorica, $idGrupo2025]
    );

    log_step('Creando o reutilizando pago de matricula extemporanea 2026-1');
    $idPago = upsert_pago_matricula_extemporanea(
        $pdo,
        $admitido,
        $pagoOperacion,
        $pagoMonto,
        $pagoFecha,
        $idCanalPagoCaja,
        $idConceptoMatriculaExtemporanea,
        $voucherDestinoRelativo
    );

    if ($apply) {
        copiar_voucher_si_existe($voucherOrigen, $voucherDestinoRelativo);
    } else {
        log_step("Voucher listo para copiar al aplicar: public/{$voucherDestinoRelativo}");
    }

    log_step('Buscando o creando matricula regular 2026-1 para los 3 cursos pendientes');
    $matriculaRegular = find_matricula_regular(
        $pdo,
        (int) $admitido['id_admitido'],
        $idGrupo2026,
        $cursosPendientes
    );

    if (!$matriculaRegular) {
        $codigoMatricula = next_codigo_matricula($pdo);

        exec_sql(
            $pdo,
            'INSERT INTO tbl_matricula
                (id_matricula_gestion, id_admitido, ciclo, codigo, fecha_matricula, creditos_disponibles, id_pago, estado)
             VALUES
                (NULL, ?, 1, ?, ?, 22, ?, 1)',
            [$admitido['id_admitido'], $codigoMatricula, $fechaRegularizacion, $idPago]
        );

        $idMatriculaRegular = (int) $pdo->lastInsertId();
        log_step("Matricula regular creada: {$idMatriculaRegular} ({$codigoMatricula})");
    } else {
        $idMatriculaRegular = (int) $matriculaRegular['id_matricula'];
        log_step("Matricula regular existente reutilizada: {$idMatriculaRegular}");

        exec_sql(
            $pdo,
            'UPDATE tbl_matricula
                SET ciclo = 1,
                    id_matricula_gestion = NULL,
                    id_pago = ?,
                    estado = 1
              WHERE id_matricula = ?',
            [$idPago, $idMatriculaRegular]
        );
    }

    foreach ($cursosPendientes as $idCursoProgramaPlan) {
        upsert_curso_pendiente(
            $pdo,
            $idMatriculaRegular,
            $idCursoProgramaPlan,
            $idGrupo2026,
            $idReingreso,
            $periodoRegularizacion
        );
    }

    log_step('Marcando prematriculas activas del admitido como cerradas, si existieran');
    exec_sql(
        $pdo,
        'UPDATE tbl_prematricula_curso
            SET estado = 0
          WHERE id_admitido = ?
            AND estado = 1',
        [$admitido['id_admitido']]
    );

    log_step('Verificacion final');
    print_rows(
        $pdo,
        'SELECT tm.id_matricula, tm.codigo, tm.ciclo, tm.id_matricula_gestion, tm.id_pago,
                tmc.id_matricula_curso, tmc.id_curso_programa_plan, c.curso_codigo,
                c.curso_nombre, tmc.id_programa_proceso_grupo, tmc.periodo,
                tmc.nota_promedio_final, tmc.estado AS curso_estado, tmc.activo,
                tmc.es_acta_reingreso, tmc.id_reingreso
           FROM tbl_matricula tm
           JOIN tbl_matricula_curso tmc ON tmc.id_matricula = tm.id_matricula
           JOIN curso_programa_plan cpp ON cpp.id_curso_programa_plan = tmc.id_curso_programa_plan
           JOIN curso c ON c.id_curso = cpp.id_curso
          WHERE tm.id_admitido = ?
          ORDER BY tm.id_matricula, c.curso_codigo',
        [$admitido['id_admitido']]
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

function exec_sql(PDO $pdo, string $sql, array $params = []): int
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    echo '  filas afectadas: ' . $statement->rowCount() . "\n";

    return $statement->rowCount();
}

function log_step(string $message): void
{
    echo "\n- {$message}\n";
}

function fail(string $message): void
{
    throw new RuntimeException($message);
}

function next_codigo_matricula(PDO $pdo): string
{
    $year = date('Y');
    $last = one(
        $pdo,
        'SELECT codigo FROM tbl_matricula ORDER BY id_matricula DESC LIMIT 1'
    );

    if (!$last || empty($last['codigo'])) {
        return 'M' . $year . str_pad('1', 5, '0', STR_PAD_LEFT);
    }

    $number = (int) substr((string) $last['codigo'], 5);
    $number++;

    return 'M' . $year . str_pad((string) $number, 5, '0', STR_PAD_LEFT);
}

function find_matricula_regular(PDO $pdo, int $idAdmitido, int $idGrupo, array $cursosPendientes): ?array
{
    $placeholders = implode(',', array_fill(0, count($cursosPendientes), '?'));
    $params = array_merge([$idAdmitido, $idGrupo], $cursosPendientes);

    return one(
        $pdo,
        "SELECT tm.*
           FROM tbl_matricula tm
           JOIN tbl_matricula_curso tmc ON tmc.id_matricula = tm.id_matricula
          WHERE tm.id_admitido = ?
            AND tm.ciclo = 1
            AND tm.estado = 1
            AND tmc.id_programa_proceso_grupo = ?
            AND tmc.id_curso_programa_plan IN ({$placeholders})
          GROUP BY tm.id_matricula
          ORDER BY tm.id_matricula DESC
          LIMIT 1",
        $params
    );
}

function upsert_curso_pendiente(
    PDO $pdo,
    int $idMatricula,
    int $idCursoProgramaPlan,
    int $idGrupo,
    int $idReingreso,
    string $periodo
): void {
    $existente = one(
        $pdo,
        'SELECT * FROM tbl_matricula_curso
          WHERE id_matricula = ?
            AND id_curso_programa_plan = ?',
        [$idMatricula, $idCursoProgramaPlan]
    );

    if ($existente) {
        log_step("Actualizando curso pendiente {$idCursoProgramaPlan}");
        exec_sql(
            $pdo,
            'UPDATE tbl_matricula_curso
                SET id_programa_proceso_grupo = ?,
                    id_docente = NULL,
                    periodo = ?,
                    es_acta_reingreso = 1,
                    id_reingreso = ?,
                    nota_promedio_final = NULL,
                    nota_evaluacion_permanente = NULL,
                    nota_evaluacion_medio_curso = NULL,
                    nota_evaluacion_final = NULL,
                    nota_observacion = NULL,
                    fecha_ingreso_nota = NULL,
                    estado = 1,
                    activo = 1
              WHERE id_matricula_curso = ?',
            [$idGrupo, $periodo, $idReingreso, $existente['id_matricula_curso']]
        );

        return;
    }

    log_step("Insertando curso pendiente {$idCursoProgramaPlan}");
    exec_sql(
        $pdo,
        'INSERT INTO tbl_matricula_curso
            (id_matricula, id_curso_programa_plan, id_programa_proceso_grupo,
             id_docente, periodo, es_acta_reingreso, id_reingreso, estado, activo)
         VALUES
            (?, ?, ?, NULL, ?, 1, ?, 1, 1)',
        [$idMatricula, $idCursoProgramaPlan, $idGrupo, $periodo, $idReingreso]
    );
}

function upsert_pago_matricula_extemporanea(
    PDO $pdo,
    array $admitido,
    string $operacion,
    string $monto,
    string $fecha,
    int $idCanalPago,
    int $idConceptoPago,
    string $voucherUrl
): int {
    $pago = one(
        $pdo,
        'SELECT *
           FROM pago
          WHERE pago_documento = ?
            AND pago_operacion = ?
            AND id_concepto_pago = ?
          ORDER BY id_pago DESC
          LIMIT 1',
        [$admitido['numero_documento'], $operacion, $idConceptoPago]
    );

    if ($pago) {
        exec_sql(
            $pdo,
            'UPDATE pago
                SET pago_monto = ?,
                    pago_fecha = ?,
                    pago_estado = 2,
                    pago_verificacion = 2,
                    pago_leido = 1,
                    pago_voucher_url = ?,
                    id_canal_pago = ?,
                    id_concepto_pago = ?,
                    id_persona = ?
              WHERE id_pago = ?',
            [
                $monto,
                $fecha,
                $voucherUrl,
                $idCanalPago,
                $idConceptoPago,
                $admitido['id_persona'],
                $pago['id_pago'],
            ]
        );

        log_step("Pago existente reutilizado: {$pago['id_pago']}");

        return (int) $pago['id_pago'];
    }

    exec_sql(
        $pdo,
        'INSERT INTO pago
            (pago_documento, pago_operacion, pago_monto, pago_fecha,
             pago_estado, pago_verificacion, pago_leido, pago_voucher_url,
             id_canal_pago, id_concepto_pago, id_persona)
         VALUES
            (?, ?, ?, ?, 2, 2, 1, ?, ?, ?, ?)',
        [
            $admitido['numero_documento'],
            $operacion,
            $monto,
            $fecha,
            $voucherUrl,
            $idCanalPago,
            $idConceptoPago,
            $admitido['id_persona'],
        ]
    );

    $idPago = (int) $pdo->lastInsertId();
    log_step("Pago creado: {$idPago}");

    return $idPago;
}

function copiar_voucher_si_existe(string $origen, string $destinoRelativo): void
{
    if (!is_file($origen)) {
        log_step("Voucher no copiado: no existe {$origen}");
        return;
    }

    $destino = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR
        . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $destinoRelativo);
    $directorio = dirname($destino);

    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    copy($origen, $destino);
    log_step("Voucher copiado a public/{$destinoRelativo}");
}

function print_rows(PDO $pdo, string $sql, array $params = []): void
{
    $statement = $pdo->prepare($sql);
    $statement->execute($params);

    foreach ($statement->fetchAll() as $row) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    }
}
