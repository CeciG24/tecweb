<?php
include_once __DIR__ . '/database.php';

$data = array();

if (isset($_POST['id'])) {
    $id = $conexion->real_escape_string($_POST['id']);
    if ($result = $conexion->query("SELECT * FROM productos WHERE id = '{$id}'")) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if ($row) {
            foreach ($row as $key => $value) {
                $data[$key] = utf8_encode($value);
            }
        }
        $result->free();
    } else {
        die('Query Error: ' . mysqli_error($conexion));
    }
}

if (isset($_POST['nombre'])) {
    $name = $conexion->real_escape_string($_POST['nombre']);
    if ($result = $conexion->query("SELECT * FROM productos WHERE nombre LIKE '%{$name}%'")) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                foreach ($row as $key => $value) {
                    $row[$key] = utf8_encode($value);
                }
                $data[] = $row;
            }
        }
        $result->free();
    } else {
        die('Query Error: ' . mysqli_error($conexion));
    }
}

$conexion->close();
echo json_encode($data, JSON_PRETTY_PRINT);
