<?php
$errores = [];
$exito = false;
$ruta_final = '';

const TAMANO_MAX = 2 * 1024 * 1024; // 2 MB
const DIRECTORIO = __DIR__ . '/uploads/';
$tipos_permitidos = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] === UPLOAD_ERR_NO_FILE) {
        $errores[] = "Debes seleccionar un archivo.";
    } elseif ($_FILES['archivo']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['archivo']['error'] === UPLOAD_ERR_FORM_SIZE) {
        $errores[] = "El archivo supera el tamaño máximo permitido (2MB).";
    } elseif ($_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        $errores[] = "Error al subir el archivo (código " . $_FILES['archivo']['error'] . ").";
    } else {
        $archivo = $_FILES['archivo'];

        if ($archivo['size'] > TAMANO_MAX) {
            $errores[] = "El archivo supera el tamaño máximo permitido (2MB).";
        }

        // Se comprueba el tipo real del contenido, no solo la extensión
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($archivo['tmp_name']);

        if (!array_key_exists($mime, $tipos_permitidos)) {
            $errores[] = "Solo se permiten imágenes JPG, PNG o GIF.";
        }

        if (empty($errores)) {
            if (!is_dir(DIRECTORIO) && !mkdir(DIRECTORIO, 0755, true)) {
                $errores[] = "No se pudo crear el directorio de destino.";
            } else {
                $nombre_nuevo = uniqid('img_', true) . '.' . $tipos_permitidos[$mime];
                $ruta_final = DIRECTORIO . $nombre_nuevo;

                if (move_uploaded_file($archivo['tmp_name'], $ruta_final)) {
                    $exito = true;
                    $nombre_guardado = $nombre_nuevo;
                } else {
                    $errores[] = "No se pudo guardar el archivo en el servidor.";
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subida de Archivos</title>
</head>
<body>

<?php if ($exito): ?>

    <h2>¡Archivo subido correctamente!</h2>
    <p>Guardado como: <?php echo htmlspecialchars($nombre_guardado); ?></p>
    <img src="uploads/<?php echo htmlspecialchars($nombre_guardado); ?>" alt="Imagen subida" style="max-width:300px;">
    <br><br>
    <a href="ex4_subida.php">Subir otra imagen</a>

<?php else: ?>

    <h2>Subir una imagen</h2>

    <?php foreach ($errores as $error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endforeach; ?>

    <form action="ex4_subida.php" method="post" enctype="multipart/form-data">

        <label for="archivo">Selecciona una imagen (JPG, PNG o GIF, máx. 2MB):</label><br>
        <input type="file" id="archivo" name="archivo" accept=".jpg,.jpeg,.png,.gif" required><br><br>

        <button type="submit">Subir</button>

    </form>

<?php endif; ?>

</body>
</html>
