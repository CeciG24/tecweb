<?php
// Recibir datos del formulario
$nombre   = $_POST['nombre'];
$marca    = $_POST['marca'];
$modelo   = $_POST['modelo'];
$precio   = $_POST['precio'];
$detalles = $_POST['detalles'];
$unidades = $_POST['unidades'];

// Manejo del archivo de imagen (nombre base, sin guardar físicamente en este ejemplo)
$imagen   = $_FILES['imagen']['name'];

// 1️⃣ Conexión a la base de datos
@$link = new mysqli('localhost', 'root', '', 'marketzone');
if ($link->connect_errno) {
    die('<h3>Error de conexión:</h3> ' . $link->connect_error);
}

// 2️⃣ Validar duplicados: nombre, modelo y marca
$sql_check = "SELECT * FROM productos 
              WHERE nombre = '$nombre' AND modelo = '$modelo' AND marca = '$marca'"; // AND eliminado = 0";

$result = $link->query($sql_check);

if ($result->num_rows > 0) {
    echo "<h3> Error:</h3> Ya existe un producto con el mismo nombre, modelo y marca.";
    $link->close();
    exit;
}

// Query de inserción con `column names` y campo `eliminado`
/*
$sql_insert = "INSERT INTO productos VALUES (null, '$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen', 0)";
*/

$sql_insert = "INSERT INTO productos (nombre, marca, modelo, precio, detalles, unidades, imagen) VALUES ('$nombre', '$marca', '$modelo', $precio, '$detalles', $unidades, '$imagen')";

// Ejecutar la query
if ($link->query($sql_insert)) {
    echo "<h2>Producto insertado correctamente</h2>";
    echo "<p><strong>ID generado:</strong> " . $link->insert_id . "</p>";
    echo "<ul>
            <li><strong>Nombre:</strong> $nombre</li>
            <li><strong>Marca:</strong> $marca</li>
            <li><strong>Modelo:</strong> $modelo</li>
            <li><strong>Precio:</strong> $precio</li>
            <li><strong>Detalles:</strong> $detalles</li>
            <li><strong>Unidades:</strong> $unidades</li>
            <li><strong>Imagen:</strong> $imagen</li>
          </ul>";
} else {
    echo "<h3> Error al insertar producto:</h3>" . $link->error;
}

$link->close();
