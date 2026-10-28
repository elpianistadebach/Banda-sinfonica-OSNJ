<?php

function getConnection(): PDO
{
    // He colocado tus datos exactos como valores por defecto.
    // Solo debes reemplazar 'EscribeTuContrasenaAqui' por tu clave real.
    $host = getenv('DB_HOST') ?: 'db.uwfatpedbwaakhrrggzl.supabase.co';
    $port = getenv('DB_PORT') ?: '5432'; 
    $user = getenv('DB_USER') ?: 'postgres';
    $pass = getenv('DB_PASS') ?: 'EscribeTuContrasenaAqui'; 
    $db   = getenv('DB_NAME') ?: 'postgres';

    // DSN (Data Source Name) ensambla la cadena de conexión que exige PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    try {
        $conexion = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Obliga a PHP a detenerse y mostrar el error exacto si falla el SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Entrega los resultados como un arreglo con los nombres de las columnas
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Delega la seguridad de las consultas preparadas al motor de Supabase
        ]);
        
        return $conexion;
    } catch (PDOException $e) {
        throw new Exception('No se pudo conectar a Supabase: ' . $e->getMessage());
    }
}
