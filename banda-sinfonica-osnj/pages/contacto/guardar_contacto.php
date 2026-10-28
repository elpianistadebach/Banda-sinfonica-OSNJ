<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$nombre  = trim($_POST['nombre'] ?? '');
$correo  = trim($_POST['correo'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if ($nombre === '' || $correo === '' || $mensaje === '') {
    header('Location: index.html');
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.html');
    exit;
}

try {
    $conexion = getConnection();

    $conexion->query("CREATE TABLE IF NOT EXISTS mensajes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        correo VARCHAR(150) NOT NULL,
        mensaje TEXT NOT NULL,
        creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $stmt = $conexion->prepare("INSERT INTO mensajes (nombre, correo, mensaje) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception('No se pudo preparar la consulta.');
    }

    $stmt->bind_param('sss', $nombre, $correo, $mensaje);
    $stmt->execute();
    $stmt->close();
    $conexion->close();
} catch (Throwable $e) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mensaje enviado</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #0f172a, #1e3a8a);
      color: #fff;
    }
    .mensaje {
      background: rgba(255, 255, 255, 0.95);
      color: #0f172a;
      padding: 2.5rem;
      border-radius: 16px;
      box-shadow: 0 12px 30px rgba(0,0,0,0.25);
      text-align: center;
      max-width: 460px;
      width: 90%;
    }
    .mensaje h2 {
      margin-top: 0;
      margin-bottom: 0.8rem;
      font-size: 1.8rem;
    }
    .mensaje p {
      font-size: 1rem;
      margin: 0;
      line-height: 1.6;
    }
  </style>
</head>
<body>
  <div class="mensaje">
    <h2>¡Gracias por tu mensaje!</h2>
    <p>Tu mensaje fue enviado correctamente.</p>
  </div>
  <script>
    setTimeout(function () {
      window.location.href = 'index.html';
    }, 2500);
  </script>
</body>
</html>
