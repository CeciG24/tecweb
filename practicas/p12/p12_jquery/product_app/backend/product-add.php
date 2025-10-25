<?php
include('database.php');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data['id'])) {
    $id = mysqli_real_escape_string($conexion, $data['id']);
    $nombre = mysqli_real_escape_string($conexion, $data['nombre']);
    $marca = mysqli_real_escape_string($conexion, $data['marca']);
    $modelo = mysqli_real_escape_string($conexion, $data['modelo']);
    $precio = floatval($data['precio']); // asegurarse que sea número
    $detalles = mysqli_real_escape_string($conexion, $data['detalles']);
    $unidades = intval($data['unidades']);
    $imagen = mysqli_real_escape_string($conexion, $data['imagen']);

    $query = "UPDATE productos 
              SET nombre='$nombre', marca='$marca', modelo='$modelo', 
                  precio=$precio, detalles='$detalles', unidades=$unidades, imagen='$imagen'
              WHERE id=$id";

    if (mysqli_query($conexion, $query)) {
        $response = [
            'status' => 'success',
            'message' => 'Producto actualizado correctamente'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Error al actualizar: ' . mysqli_error($conexion)
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'No se recibió el ID del producto'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>