<?php
header('Content-Type: application/json; charset=utf-8');

include_once __DIR__ . '/database.php';

// SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
$producto = file_get_contents('php://input');
$data = array(
    'status'  => 'error',
    'message' => 'Error al actualizar el producto'
);

if (!empty($producto)) {
    // SE TRANSFORMA EL STRING DEL JSON A OBJETO
    $jsonOBJ = json_decode($producto);

    // VERIFICAR QUE VENGA EL ID
    if (!isset($jsonOBJ->id) || empty($jsonOBJ->id)) {
        $data['message'] = "ERROR: No se recibió el ID del producto";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // VALIDACIONES DE CAMPOS OBLIGATORIOS
    if (empty(trim($jsonOBJ->nombre))) {
        $data['message'] = "ERROR: El nombre del producto es obligatorio";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if (empty(trim($jsonOBJ->marca))) {
        $data['message'] = "ERROR: La marca es obligatoria";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if (empty(trim($jsonOBJ->modelo))) {
        $data['message'] = "ERROR: El modelo es obligatorio";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if (!isset($jsonOBJ->precio) || $jsonOBJ->precio <= 0) {
        $data['message'] = "ERROR: El precio debe ser mayor a 0";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if (!isset($jsonOBJ->unidades) || $jsonOBJ->unidades < 0) {
        $data['message'] = "ERROR: Las unidades deben ser mayor o igual a 0";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    // VALIDACIONES DE LONGITUD
    if (strlen($jsonOBJ->nombre) > 100) {
        $data['message'] = "ERROR: El nombre no puede exceder 100 caracteres";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if (strlen($jsonOBJ->marca) > 25) {
        $data['message'] = "ERROR: La marca no puede exceder 25 caracteres";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if (strlen($jsonOBJ->modelo) > 25) {
        $data['message'] = "ERROR: El modelo no puede exceder 25 caracteres";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    if (isset($jsonOBJ->detalles) && strlen($jsonOBJ->detalles) > 250) {
        $data['message'] = "ERROR: Los detalles no pueden exceder 250 caracteres";
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    $conexion->set_charset("utf8");

    $id = mysqli_real_escape_string($conexion, $jsonOBJ->id);
    $nombre = mysqli_real_escape_string($conexion, trim($jsonOBJ->nombre));
    $marca = mysqli_real_escape_string($conexion, trim($jsonOBJ->marca));
    $modelo = mysqli_real_escape_string($conexion, trim($jsonOBJ->modelo));
    $precio = floatval($jsonOBJ->precio);
    $detalles = mysqli_real_escape_string($conexion, $jsonOBJ->detalles);
    $unidades = intval($jsonOBJ->unidades);
    $imagen = mysqli_real_escape_string($conexion, $jsonOBJ->imagen);

    // VERIFICAR QUE EL NOMBRE NO EXISTA EN OTRO PRODUCTO (excepto el actual)
    $sql = "SELECT * FROM productos WHERE nombre = '{$nombre}' AND id != {$id} AND eliminado = 0";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $data['message'] = "ERROR: Ya existe otro producto con ese nombre";
        echo json_encode($data, JSON_PRETTY_PRINT);
        $result->free();
        $conexion->close();
        exit;
    }

    $result->free();

    // SE REALIZA LA QUERY DE ACTUALIZACIÓN
    $sql = "UPDATE productos SET 
            nombre = '{$nombre}',
            marca = '{$marca}',
            modelo = '{$modelo}',
            precio = {$precio},
            detalles = '{$detalles}',
            unidades = {$unidades},
            imagen = '{$imagen}'
            WHERE id = {$id} AND eliminado = 0";

    if ($conexion->query($sql)) {
        $data['status'] = "success";
        $data['message'] = "Producto actualizado correctamente";
    } else {
        $data['message'] = "ERROR: No se ejecutó $sql. " . mysqli_error($conexion);
    }

    $conexion->close();
}

// SE HACE LA CONVERSIÓN DE ARRAY A JSON
echo json_encode($data, JSON_PRETTY_PRINT);
?>