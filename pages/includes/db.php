<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function getConnection(): mysqli
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $db   = getenv('DB_NAME') ?: 'bandasinfonica';

    $conexion = new mysqli($host, $user, $pass);

    if ($conexion->connect_error) {
        throw new Exception('No se pudo conectar al servidor MySQL.');
    }

    $conexion->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conexion->select_db($db);
    $conexion->set_charset('utf8mb4');

    return $conexion;
}
