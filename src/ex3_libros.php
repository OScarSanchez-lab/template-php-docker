<?php
class Libro
{
    private string $titulo;
    private string $autor;
    private int $anio;
    private int $paginas;

    public function setTitulo(string $titulo): void { $this->titulo = $titulo; }
    public function setAutor(string $autor): void { $this->autor = $autor; }
    public function setAnio(int $anio): void { $this->anio = $anio; }
    public function setPaginas(int $paginas): void { $this->paginas = $paginas; }

    public function getTitulo(): string { return $this->titulo; }
    public function getAutor(): string { return $this->autor; }
    public function getAnio(): int { return $this->anio; }
    public function getPaginas(): int { return $this->paginas; }
}

$errores = [];
$mostrar_datos = false;
$libro = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $anio = trim($_POST['anio'] ?? '');
    $paginas = trim($_POST['paginas'] ?? '');

    if ($titulo === '' || $autor === '' || $anio === '' || $paginas === '') {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if ($anio !== '' && !ctype_digit($anio)) {
        $errores[] = "El año de publicación debe ser un número entero.";
    } elseif ($anio !== '' && (int) $anio > (int) date('Y')) {
        $errores[] = "El año de publicación no puede ser futuro.";
    }

    if ($paginas !== '' && !ctype_digit($paginas)) {
        $errores[] = "El número de páginas debe ser un número entero.";
    } elseif ($paginas !== '' && (int) $paginas < 1) {
        $errores[] = "El número de páginas debe ser mayor que 0.";
    }

    if (empty($errores)) {
        $libro = new Libro();
        $libro->setTitulo($titulo);
        $libro->setAutor($autor);
        $libro->setAnio((int) $anio);
        $libro->setPaginas((int) $paginas);
        $mostrar_datos = true;
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Libros</title>
</head>
<body>

<?php if ($mostrar_datos): ?>

    <h2>Resumen del libro</h2>
    <p>Título: <?php echo htmlspecialchars($libro->getTitulo()); ?></p>
    <p>Autor: <?php echo htmlspecialchars($libro->getAutor()); ?></p>
    <p>Año de publicación: <?php echo $libro->getAnio(); ?></p>
    <p>Número de páginas: <?php echo $libro->getPaginas(); ?></p>
    <br>
    <a href="ex3_libros.php">Añadir otro libro</a>

<?php else: ?>

    <h2>Registrar un libro</h2>

    <?php foreach ($errores as $error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endforeach; ?>

    <form action="ex3_libros.php" method="post">

        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="autor">Autor:</label><br>
        <input type="text" id="autor" name="autor" required><br><br>

        <label for="anio">Año de publicación:</label><br>
        <input type="text" id="anio" name="anio" required><br><br>

        <label for="paginas">Número de páginas:</label><br>
        <input type="text" id="paginas" name="paginas" required><br><br>

        <button type="submit">Enviar</button>

    </form>

<?php endif; ?>

</body>
</html>
