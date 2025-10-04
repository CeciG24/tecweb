<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 3</title>
</head>

<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar, $_7var, myvar, $myvar, $var7, $_element1, $house*5</p>
    <?php
    //AQUI VA MI CÓDIGO PHP
    $_myvar;
    $_7var;
    //myvar;       // Inválida
    $myvar;
    $var7;
    $_element1;
    //$house*5;     // Invalida
    $a = "ManejadorSQL";
    $b = 'MySQL';
    $c = &$a;
    echo '<h4>Respuesta:</h4>';

    echo '<ul>';
    echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
    echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
    echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
    echo '<li>$myvar es válida porque inicia con una letra.</li>';
    echo '<li>$var7 es válida porque inicia con una letra.</li>';
    echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
    echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
    echo '</ul>';
    echo '<p>';
    echo $a . "<br>";
    echo $b . "<br>";
    echo $c . "<br>";
    $a = "PHP server";
    $b = &$a;
    echo $a . "<br>";
    echo $b . "<br>";
    $a = "PHP5";
    echo $a . "<br>";
    $z[] = &$a;
    $b = "5a version de PHP";
    echo $b . "<br>";
    $c = $b * 10;
    echo $c . "<br>";
    $a .= $b;
    echo $a . "<br>";
    $b *= $c;

    echo $b . "<br>";
    $z[0] = "MySQL";
    echo $z[0] . "<br>";

    // Usando $GLOBALS
    echo "<br>Con GLOBALS:<br>";
    echo "\$a = ";
    var_dump($GLOBALS["a"]);
    echo "\$b = ";
    var_dump($GLOBALS["b"]);
    echo "\$c = ";
    var_dump($GLOBALS["c"]);
    echo "\$z = ";
    print_r($GLOBALS["z"]);

    echo '</p>';
    $a = "7 personas";
    $b = (int) $a;
    $a = "9E3";
    $c = (float) $a;

    $a = "0";              // string "0" en booleano → false
    $b = "TRUE";           // string "TRUE" en booleano → true (no es vacío ni "0")
    $c = FALSE;            // false
    $d = ($a or $b);       // false OR true → true
    $e = ($a and $c);      // false AND false → false
    $f = ($a xor $b);      // false XOR true → true

    // Mostramos con var_dump
    var_dump($a); // string(1) "0"
    var_dump((bool)$a); // bool(false)

    var_dump($b); // string(4) "TRUE"
    var_dump((bool)$b); // bool(true)

    var_dump($c); // bool(false)
    var_dump($d); // bool(true)
    var_dump($e); // bool(false)
    var_dump($f); // bool(true)

    echo "c = " . var_export($c, true) . "<br>";
    echo "e = " . var_export($e, true) . "<br>";

    echo '<p>';
    echo "La versión del servidor es: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
    echo "El nombre del sistema operativo es: " . PHP_OS . "<br>";
    echo "Idioma del navegador: " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "<br>";
    echo '</p>';
    ?>
    <p>
        <a href="https://validator.w3.org/check?uri=referer"><img
                src="https://www.w3.org/Icons/valid-xhtml11" alt="Valid XHTML 1.1" height="31" width="88" /></a>
    </p>
</body>

</html>