<?php
$id = intval($_GET['id']);  // convierte a número seguro

if (!$id) {
    die("ID de producto no proporcionado.");
}

$link = mysqli_connect("localhost", "root", "", "marketzone");
if ($link === false) {
    die("ERROR: No pudo conectarse con la DB. " . mysqli_connect_error());
}

$sql = "SELECT * FROM productos WHERE id = $id";

$result = mysqli_query($link, $sql);
$producto = mysqli_fetch_assoc($result);

if (!$producto) {
    die("Producto no encontrado.");
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar producto</title>
</head>

<body>
    <h2>Actualizar producto</h2>
    <form method="POST" action="update_producto.php">
        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

        Nombre: <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>" maxlength="100"
            required><br>
        Marca:
        <select name="marca" required>
            <option value="Samsung" <?php if ($producto['marca'] == 'Samsung') echo 'selected'; ?>>Samsung</option>
            <option value="Xiaomi" <?php if ($producto['marca'] == 'Xiaomi') echo 'selected'; ?>>Xiaomi</option>
            <option value="Apple" <?php if ($producto['marca'] == 'Apple') echo 'selected'; ?>>Apple</option>
            <option value="Sony" <?php if ($producto['marca'] == 'Sony') echo 'selected'; ?>>Sony</option>
        </select><br>
        Modelo: <input type="text" name="modelo" value="<?php echo $producto['modelo']; ?>" maxlength="25"
            pattern="[A-Za-z0-9]+"><br>
        Precio: <input type="number" name="precio" value="<?php echo $producto['precio']; ?>" step="0.01"
            min="99.99"><br>
        Detalles: <textarea name="detalles" maxlength="250"><?php echo $producto['detalles']; ?></textarea><br>
        Unidades: <input type="number" name="unidades" value="<?php echo $producto['unidades']; ?>" min="0"
            required><br>
        Imagen: <input type="text" name="imagen" value="<?php echo $producto['imagen']; ?>"><br>

        <input type="submit" value="Guardar cambios">
    </form>
</body>

</html>