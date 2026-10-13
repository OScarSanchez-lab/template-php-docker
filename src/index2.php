<?php
session_start();

// Valores predefinidos (sin BBDD)
const USUARIO_VALIDO = "admin";
const PASSWORD_VALIDA = "1234";
const ID_FIJO = 1;

$errores = [];

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ex2_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$usuario || !$password) {
        $errores[] = "Debes introducir usuario y contraseña.";
    } elseif ($usuario === USUARIO_VALIDO && $password === PASSWORD_VALIDA) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = ID_FIJO;
        $_SESSION['usuario'] = $usuario;
    } else {
        $errores[] = "Usuario o contraseña incorrectos.";
    }
}

$logueado = isset($_SESSION['user_id']);
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de Sesión</title>
</head>
<body>

<?php if ($logueado): ?>

    <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
    <p>Tu identificador de usuario es: <?php echo (int) $_SESSION['user_id']; ?></p>
    <br>
    <a href="ex2_login.php?logout=1">Cerrar sesión</a>

<?php else: ?>

    <h2>Iniciar sesión</h2>

    <?php foreach ($errores as $error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endforeach; ?>

    <?php if (!empty($errores)): ?>
        <p>Inténtalo de nuevo:</p>
    <?php endif; ?>

    <form action="ex2_login.php" method="post">

        <label for="usuario">Usuario:</label><br>
        <input type="text" id="usuario" name="usuario" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Entrar</button>

    </form>

    <p><small>Prueba con: admin / 1234</small></p>

<?php endif; ?>

</body>
</html>
