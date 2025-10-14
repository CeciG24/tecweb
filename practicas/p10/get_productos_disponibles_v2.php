<?php
@$link = new mysqli('localhost', 'root', '', 'marketzone');

if ($link->connect_errno) {
    die('Error de conexión: ' . $link->connect_error);
}

$result = $link->query("SELECT * FROM productos WHERE eliminado = 0");

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <h3>Lista de productos</h3>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Precio</th>
                <th>Unidades</th>
                <th>Actualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $producto['id'] ?></td>
                    <td><?= $producto['nombre'] ?></td>
                    <td><?= $producto['marca'] ?></td>
                    <td><?= $producto['modelo'] ?></td>
                    <td><?= $producto['precio'] ?></td>
                    <td><?= $producto['unidades'] ?></td>
                    <td>
                        <form action="formulario_productos_v2.php" method="get">
                            <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                            <input type="submit" value="Actualizar" class="btn btn-primary btn-sm">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>