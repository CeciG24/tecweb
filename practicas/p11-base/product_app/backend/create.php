<?php
include_once __DIR__ . '/database.php';

// Se lee el JSON recibido desde el cliente
$json = file_get_contents('php://input');

// Se convierte a un objeto PHP
$producto = json_decode($json, true);

// Validar que se haya recibido un JSON válido
if (!$producto) {
    echo json_encode(["status" => "error", "message" => "JSON inválido o vacío"]);
    exit;
}

// Extraer valores del JSON
$nombre = $conexion->real_escape_string($producto['nombre']);
$precio = $conexion->real_escape_string($producto['precio']);
$unidades = $conexion->real_escape_string($producto['unidades']);
$modelo = $conexion->real_escape_string($producto['modelo']);
$marca = $conexion->real_escape_string($producto['marca']);
$detalles = $conexion->real_escape_string($producto['detalles']);
$imagen = $conexion->real_escape_string($producto['imagen']);

// Verificar si el producto ya existe (nombre + eliminado = 0)
$query_check = "SELECT id FROM productos WHERE nombre = '$nombre' AND eliminado = 0";
$result_check = $conexion->query($query_check);

if ($result_check && $result_check->num_rows > 0) {
    // Ya existe
    $response = [
        "status" => "error",
        "message" => "El producto ya existe y no ha sido eliminado."
    ];
} else {
    // Insertar el nuevo producto
    $query_insert = "
        INSERT INTO productos (nombre, precio, unidades, modelo, marca, detalles, imagen, eliminado)
        VALUES ('$nombre', '$precio', '$unidades', '$modelo', '$marca', '$detalles', '$imagen', 0)
    ";

    if ($conexion->query($query_insert)) {
        $response = [
            "status" => "success",
            "message" => "Producto agregado correctamente."
        ];
    } else {
        $response = [
            "status" => "error",
            "message" => "Error al insertar el producto: " . $conexion->error
        ];
    }
}

$conexion->close();
echo json_encode($response);
?>