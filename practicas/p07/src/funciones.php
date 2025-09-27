<?php
function esMultiploDe5y7($num){
    
    if ($num%5==0 && $num%7==0)
        {
            echo '<h3>R= El número '.$num.' SÍ es múltiplo de 5 y 7.</h3>';
        }
    else
        {
            echo '<h3>R= El número '.$num.' NO es múltiplo de 5 y 7.</h3>';
        }
}

function generarMatriz() {
    $matriz = [];
    $numerosGenerados = 0;
    $iteraciones = 0;

    while (true) {
        $iteraciones++;

        // Generar 3 números aleatorios
        $n1 = rand(0,1000);
        $n2 = rand(0,1000);
        $n3 = rand(0,1000);
        $numerosGenerados += 3;

        // Guardar siempre la fila generada
        $matriz[] = [$n1, $n2, $n3];

        // Verificar patrón impar-par-impar
        if ($n1 % 2 != 0 && $n2 % 2 == 0 && $n3 % 2 != 0) {
            break; // Terminamos cuando aparece el patrón
        }
    }

    return [$matriz, $numerosGenerados, $iteraciones];
}

function imprimirMatriz($matriz)
{
    echo '<table border="1" cellspacing="0" cellpadding="5">';

    foreach ($matriz as $i => $fila) {
        echo '<tr>';
        foreach ($fila as $j => $valor) {

            if ($valor % 2 == 0) {
                echo "<td style='color:red;'>$valor</td>";
            } else {
                echo "<td style='color:blue;'>$valor</td>";
            }
        }
        echo '</tr>';
    }
    echo '</table>';
}

function multiploAleatorio($num){
    $numAleatorio= rand();
    while($numAleatorio % $num != 0){
        $numAleatorio = rand();
    }
    echo '<h3>R= El múltiplo de ' . $num . ' generado aleatoriamente fue: ' . $numAleatorio . '</h3>';
}

function arrayASCII(){
    $arreglo=[];
    for ($i = 97; $i < 123; $i++) {
        $arreglo[$i] = chr($i);
    }
    
    // Comenzamos tabla XHTML
    echo '<table border="1" cellspacing="0" cellpadding="5">';
    echo '<tr><th>ASCII</th><th>Caracter</th></tr>';

    // Recorremos el arreglo e imprimimos filas
    foreach ($arreglo as $key => $value) {
        echo "<tr>";
        echo "<td>$key</td>";
        echo "<td>$value</td>";
        echo "</tr>";
    }

    echo "</table>";
}

function postAge($edad,$sexo){
    if ($sexo=="femenino"&&$edad>=18&&$edad<35){
        echo '<p>Bienvenida, usted está en el rango de edad permitido.</p>';
    }else{
        echo '<p>Lo sentimos, usted no está en el rango de edad permitido o no es del genero femenino.</p>';
    }
}
function Autos($filtro = null)
{
    $arrayAutos = [
        "UBN6338" => [
            "Auto" => [
                "marca" => "HONDA",
                "modelo" => "2020",
                "tipo" => "camioneta"
            ],
            "Propietario" => [
                "nombre" => "Alfonzo Esparza",
                "ciudad" => "Puebla, Pue.",
                "direccion" => "C.U., Jardines de San Manuel"
            ]
        ],
        "UBN6339" => [
            "Auto" => [
                "marca" => "MAZDA",
                "modelo" => "2019",
                "tipo" => "sedan"
            ],
            "Propietario" => [
                "nombre" => "Ma. del Consuelo Molina",
                "ciudad" => "Puebla, Pue.",
                "direccion" => "97 oriente"
            ]
        ]
    ];

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Matrícula</th><th>Marca</th><th>Modelo</th><th>Tipo</th><th>Propietario</th><th>Ciudad</th><th>Dirección</th></tr>";

    foreach ($arrayAutos as $matricula => $info) {
        if ($filtro && $filtro != $matricula) {
            continue;
        }

        echo "<tr>";
        echo "<td>$matricula</td>";
        echo "<td>" . $info["Auto"]["marca"] . "</td>";
        echo "<td>" . $info["Auto"]["modelo"] . "</td>";
        echo "<td>" . $info["Auto"]["tipo"] . "</td>";
        echo "<td>" . $info["Propietario"]["nombre"] . "</td>";
        echo "<td>" . $info["Propietario"]["ciudad"] . "</td>";
        echo "<td>" . $info["Propietario"]["direccion"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>