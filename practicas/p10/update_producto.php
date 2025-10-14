<?php
$link = mysqli_connect("localhost", "root", "", "marketzone");
if ($link === false) {
    die("ERROR: No pudo conectarse con la DB. " . mysqli_connect_error());
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$precio = $_POST['precio'];
$detalles = $_POST['detalles'];
$unidades = $_POST['unidades'];
$imagen = $_POST['imagen'] ?: "imagenes/default.jpg"; // imagen por defecto si está vacía

$sql = "UPDATE productos 
        SET nombre='$nombre', marca='$marca', modelo='$modelo', precio=$precio, 
            detalles='$detalles', unidades=$unidades, imagen='$imagen'
        WHERE id=$id";

if (mysqli_query($link, $sql)) {
    echo "Producto actualizado correctamente.<br>";
    echo "<a href='get_productos_xhtml_v2.php'>Volver a la lista</a>";
} else {
    echo " ERROR: No se pudo actualizar. " . mysqli_error($link);
}

mysqli_close($link);
?>