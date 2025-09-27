<?php
require_once 'src/funciones.php'; // Se carga al inicio y ya está disponible en todo el script
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 7</title>
</head>

<body>
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    <?php
    if (isset($_GET['numero'])) {
        $num = $_GET['numero'];
        esMultiploDe5y7($num);
    }
    ?>

    <h2>Ejercicio 2 </h2>
    <p>Generar matriz de numeros aleatorios hasta obtener impar, par, impar</p>
    <?php
    list($matriz, $totalNumeros, $iteraciones) = generarMatriz();
    imprimirMatriz($matriz);
    echo "<p><strong>$totalNumeros</strong> números obtenidos en <strong>$iteraciones</strong> iteraciones.</p>";
    ?>

    <h2>Ejercicio 3</h2>
    <p>Utiliza un ciclo while para encontrar el primer número entero obtenido aleatoriamente,
        pero que además sea múltiplo de un número dado.</p>
    <?php
    if (isset($_GET['numero'])) {
        $num = $_GET['numero'];
        multiploAleatorio($num);
    }
    ?>

    <h2>Ejercicio 4</h2>
    <p>Tabla de valores ASCII</p>
    <?php
    arrayASCII();
    ?>

    <h2>Ejercicio 5</h2>
    <p>Ingrese su edad y su sexo (masculino o femenino)</p>
    <form action="index.php" method="post">
        Edad: <input type="number" name="age"><br>
        Sexo: <input type="text" name="sex"><br>
        <input type="submit" value="Enviar">
    </form>
    <br>
    <?php
    if (isset($_POST["age"]) && isset($_POST["sex"])) {
        $edad = $_POST["age"];
        $sexo = $_POST["sex"];
        postAge($edad, $sexo);
    }
    ?>

    <h2>Ejercicio 6</h2>
    <p>Mostrar arreglo de autos. ¿De qué manera desea consultar la información?</p>

    <form action="index.php" method="post">
        <ul>
            <li>
                <label>
                    <input type="radio" name="autos" value="matricula"
                        <?php if (isset($_POST['autos']) && $_POST['autos'] == "matricula") echo "checked"; ?>>
                    Matrícula
                </label>
            </li>
            <li>
                <label>
                    <input type="radio" name="autos" value="todos"
                        <?php if (isset($_POST['autos']) && $_POST['autos'] == "todos") echo "checked"; ?>>
                    Todos los autos
                </label>
            </li>
        </ul>

        <?php
        // Si seleccionó "matricula", muestra campo extra
        if (isset($_POST["autos"]) && $_POST["autos"] == "matricula") {
            echo 'Ingrese matrícula: <input type="text" name="matricula"><br>';
        }
        ?>
        <br>
        <input type="submit" value="Consultar">
    </form>

    <?php
    if (isset($_POST["autos"])) {
        if ($_POST["autos"] == "todos") {
            Autos();
        } elseif ($_POST["autos"] == "matricula" && !empty($_POST["matricula"])) {
            Autos($_POST["matricula"]);
        } elseif ($_POST["autos"] == "matricula" && empty($_POST["matricula"])) {
            echo "<p style='color:red;'>Por favor ingrese una matrícula.</p>";
        }
    }
    ?>

</body>

</html>