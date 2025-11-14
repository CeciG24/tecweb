<?php
include('database.php'); // tu conexión a la BD

if (isset($_GET['nombre'])) {
    $nombre = $_GET['nombre'];
    $query = "SELECT * FROM productos WHERE nombre = '$nombre' LIMIT 1";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(["existe" => true]);
    } else {
        echo json_encode(["existe" => false]);
    }
}
