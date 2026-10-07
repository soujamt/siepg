<?php

declare(strict_types=1);

/**
 * Agrega la columna inscripcion.id_inscripcion_origen para el traslado de inscripciones entre procesos.
 *
 * Cuando un programa no se apertura, la inscripcion se traslada al proceso activo desde
 * Gestion de Admision > Inscripciones > "Trasladar al Proceso Actual". El traslado crea una
 * inscripcion nueva en el proceso activo que guarda en esta columna el id de la inscripcion original
 * (que queda reservada en su proceso). Sirve para la trazabilidad y para no contar dos veces el pago
 * de inscripcion en los reportes de ingresos.
 *
 * Es idempotente: si la columna ya existe no hace nada.
 *
 * Uso:
 *   php scripts/agregar_id_inscripcion_origen.php
 *   php scripts/agregar_id_inscripcion_origen.php --apply
 *
 * Sin --apply solo muestra lo que haria (los ALTER TABLE de MySQL no se pueden revertir con una transaccion).
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

$sentencias = [
    'ALTER TABLE inscripcion ADD COLUMN id_inscripcion_origen INT NULL DEFAULT NULL AFTER id_programa_proceso',
    'ALTER TABLE inscripcion ADD INDEX id_inscripcion_origen (id_inscripcion_origen)',
];

try {
    echo "Base de datos: {$config['database']}\n";

    $columna = one(
        $pdo,
        "SELECT COLUMN_NAME FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'inscripcion' AND COLUMN_NAME = 'id_inscripcion_origen'"
    );

    if ($columna) {
        echo "\nLa columna inscripcion.id_inscripcion_origen ya existe. No hay nada que hacer.\n";
        exit(0);
    }

    if (!$apply) {
        echo "\nSimulacion. Se ejecutaria:\n";
        foreach ($sentencias as $sql) {
            echo "  {$sql};\n";
        }
        echo "\nEjecuta con --apply para aplicar los cambios.\n";
        exit(0);
    }

    foreach ($sentencias as $sql) {
        echo "\n- {$sql}\n";
        $pdo->exec($sql);
    }

    echo "\nColumna inscripcion.id_inscripcion_origen agregada correctamente.\n";
} catch (Throwable $exception) {
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
