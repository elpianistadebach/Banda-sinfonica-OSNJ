<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$accion = $_POST['accion'] ?? '';
$correoElectronico = trim($_POST['correo_electronico'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$nombreCompleto = trim($_POST['nombre_completo'] ?? '');
$confirmarContrasena = trim($_POST['confirmar_contrasena'] ?? '');

if ($accion === 'login') {
    if ($correoElectronico === '' || $contrasena === '') {
        header('Location: index.html');
        exit;
    }

    try {
        $conexion = getConnection();

        $conexion->query("CREATE TABLE IF NOT EXISTS sesion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            correo_electronico VARCHAR(150) NOT NULL,
            contrasena VARCHAR(255) NOT NULL,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $conexion->prepare("INSERT INTO sesion (correo_electronico, contrasena) VALUES (?, ?)");

        if (!$stmt) {
            throw new Exception('No se pudo preparar la consulta.');
        }

        $stmt->bind_param('ss', $correoElectronico, $hash);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    } catch (Throwable $e) {
        header('Location: index.html');
        exit;
    }

    header('Location: exito.html');
    exit;
}

if ($accion === 'registro') {
    if ($nombreCompleto === '' || $correoElectronico === '' || $contrasena === '' || $confirmarContrasena === '') {
        header('Location: index.html');
        exit;
    }

    if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
        header('Location: index.html');
        exit;
    }

    if ($contrasena !== $confirmarContrasena) {
        header('Location: index.html');
        exit;

    $conexion = getConnection();
    $stmt = $conexion->prepare("SELECT id FROM registre WHERE correo_electronico = ?");
    $stmt->bind_param('s', $correoElectronico);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $stmt->close();
        $conexion->close();
        header('Location: index.html');
        exit;
    }

    try {
        $conexion = getConnection();

        $conexion->query("CREATE TABLE IF NOT EXISTS registre (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_completo VARCHAR(150) NOT NULL,
            correo_electronico VARCHAR(150) NOT NULL UNIQUE,
            contrasena VARCHAR(255) NOT NULL,
            confirmar_contrasena VARCHAR(255) NOT NULL,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $conexion->prepare("INSERT INTO registre (nombre_completo, correo_electronico, contrasena) VALUES (?, ?, ?)");

        if (!$stmt) {
            throw new Exception('No se pudo preparar la consulta.');
        }

        $stmt->bind_param('ssss', $nombreCompleto, $correoElectronico, $hash, $hash);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    } catch (Throwable $e) {
        header('Location: index.html');
        alert ('Error de conexión a la base de datos');
        exit;
    }

    header('Location: exito.html');
    exit;
}

header('Location: index.html');
exit;
