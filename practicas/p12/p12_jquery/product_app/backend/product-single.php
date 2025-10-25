<?php
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . '/database.php';

// SE CREA EL ARREGLO QUE SE VA A DEVOLVER
$data = array();

// SE VERIFICA QUE SE HAYA ENVIADO EL ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SE REALIZA LA QUERY DE BÚSQUEDA
    $sql = "SELECT * FROM productos WHERE id = {$id} AND eliminado = 0";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        // SE OBTIENE EL PRODUCTO
        $data = $result->fetch_assoc();
    }

    $result->free();
}

$conexion->close();

// SE DEVUELVE EL PRODUCTO EN FORMATO JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>