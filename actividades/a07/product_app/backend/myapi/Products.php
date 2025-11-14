<?php

namespace App;

require_once __DIR__ . '/DataBase.php';

class Products extends DataBase
{
    protected $data;

    public function __construct(
        string $dbName = "marketzone",
        string $host = "localhost",
        string $user = "root",
        string $pass = ""
    ) {
        parent::__construct($host, $user, $pass, $dbName);
        $this->data = [];
    }

    /* ===========================================================
       GETDATA(): string
       =========================================================== */
    public function getData(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }

    /* ===========================================================
       ADD (Object): void
       =========================================================== */
    public function add(object $jsonOBJ): void
    {
        $nombre   = $this->conexion->real_escape_string($jsonOBJ->nombre);
        $marca    = $this->conexion->real_escape_string($jsonOBJ->marca);
        $modelo   = $this->conexion->real_escape_string($jsonOBJ->modelo);
        $detalles = $this->conexion->real_escape_string($jsonOBJ->detalles);
        $imagen   = $this->conexion->real_escape_string($jsonOBJ->imagen);

        $sql = "
            INSERT INTO productos VALUES (
                NULL,
                '$nombre',
                '$marca',
                '$modelo',
                {$jsonOBJ->precio},
                '$detalles',
                {$jsonOBJ->unidades},
                '$imagen',
                0
            )
        ";

        $result = $this->conexion->query($sql);

        $this->data = $result
            ? ['status' => 'success', 'message' => 'Producto agregado']
            : ['status' => 'error', 'message' => 'Error al insertar: ' . $this->conexion->error];
    }

    /* ===========================================================
       DELETE(string): void  (soft delete -> eliminado=1)
       =========================================================== */
    public function delete(string $id): void
    {
        $id = $this->conexion->real_escape_string($id);
        $sql = "UPDATE productos SET eliminado = 1 WHERE id = '$id'";

        $result = $this->conexion->query($sql);

        $this->data = $result
            ? ['status' => 'success', 'message' => 'Producto eliminado']
            : ['status' => 'error', 'message' => 'Error al eliminar: ' . $this->conexion->error];
    }

    /* ===========================================================
       EDIT(Object): void
       =========================================================== */
    public function edit(object $jsonOBJ): void
    {
        $id       = (int)$jsonOBJ->id;
        $nombre   = $this->conexion->real_escape_string($jsonOBJ->nombre);
        $marca    = $this->conexion->real_escape_string($jsonOBJ->marca);
        $modelo   = $this->conexion->real_escape_string($jsonOBJ->modelo);
        $precio   = (float)$jsonOBJ->precio;
        $detalles = $this->conexion->real_escape_string($jsonOBJ->detalles);
        $unidades = (int)$jsonOBJ->unidades;
        $imagen   = $this->conexion->real_escape_string($jsonOBJ->imagen);

        $sql = "
            UPDATE productos SET
                nombre   = '$nombre',
                marca    = '$marca',
                modelo   = '$modelo',
                precio   = $precio,
                detalles = '$detalles',
                unidades = $unidades,
                imagen   = '$imagen'
            WHERE id = $id
        ";

        $result = $this->conexion->query($sql);

        $this->data = $result
            ? ['status' => 'success', 'message' => 'Producto modificado']
            : ['status' => 'error', 'message' => 'Error al actualizar: ' . $this->conexion->error];
    }

    /* ===========================================================
       LIST(): void
       =========================================================== */
    public function list(): void
    {
        $sql = "SELECT * FROM productos WHERE eliminado = 0";
        $result = $this->conexion->query($sql);

        $this->data = [];

        while ($row = $result->fetch_assoc()) {
            $this->data[] = $row;
        }
    }

    /* ===========================================================
       SEARCH(string): void
       =========================================================== */
    public function search(string $value): void
    {
        $value = $this->conexion->real_escape_string($value);

        $sql = "
            SELECT * FROM productos
            WHERE (id = '$value'
                OR nombre LIKE '%$value%'
                OR marca LIKE '%$value%'
                OR detalles LIKE '%$value%')
            AND eliminado = 0
        ";

        $result = $this->conexion->query($sql);

        $this->data = [];

        while ($row = $result->fetch_assoc()) {
            $this->data[] = $row;
        }
    }

    /* ===========================================================
       SINGLE(string): void  (por ID)
       =========================================================== */
    public function single(string $id): void
    {
        $id = (int)$id;

        $sql = "SELECT * FROM productos WHERE id = $id AND eliminado = 0";

        $result = $this->conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $this->data = $result->fetch_assoc();
        } else {
            $this->data = [
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ];
        }
    }

    /* ===========================================================
       SINGLEBYNAME(string): void
       =========================================================== */
    public function singleByName(string $name): void
    {
        $name = $this->conexion->real_escape_string($name);

        $sql = "SELECT * FROM productos WHERE nombre = '$name' AND eliminado = 0";

        $result = $this->conexion->query($sql);

        if ($result && $result->num_rows > 0) {
            $this->data = $result->fetch_assoc();
        } else {
            $this->data = [
                'status' => 'error',
                'message' => 'Producto no encontrado'
            ];
        }
    }

}
?>