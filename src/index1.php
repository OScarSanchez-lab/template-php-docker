<?php
$errores = [];
$mostrar_datos = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fecha = trim($_POST['fecha_nacimiento'] ?? '');

    if (!$nombre || !$apellido || !$email || !$fecha) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no tiene un formato válido.";
    }

    if ($fecha) {
        $fecha_dt = date_create($fecha);
        $hoy = date_create();

        if (!$fecha_dt) {
            $errores[] = "Fecha no válida.";
        } elseif ($fecha_dt > $hoy) {
            $errores[] = "La fecha no puede ser futura.";
        } elseif (date_diff($fecha_dt, $hoy)->y < 18) {
            $errores[] = "Debes tener al menos 18 años.";
        }
    }

    if (empty($errores)) {
        $mostrar_datos = true;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro</title>
</head>
<body>

<?php if ($mostrar_datos): ?>

    <h2>Datos recibidos:</h2>
    <p>Nombre: <?php echo htmlspecialchars($nombre); ?></p>
    <p>Apellido: <?php echo htmlspecialchars($apellido); ?></p>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p>Fecha de nacimiento: <?php echo htmlspecialchars($fecha); ?></p>
    <br>
    <a href="ex1_registro.php">Volver</a>

<?php else: ?>

    <h2>Formulario de Registro</h2>

    <?php foreach ($errores as $error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endforeach; ?>

    <form action="ex1_registro.php" method="post">

        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" required><br><br>

        <label for="email">Correo electrónico:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="fecha_nacimiento">Fecha de nacimiento:</label><br>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required><br><br>

        <button type="submit">Enviar</button>

    </form>

<?php endif; ?>

</body>
</html>
