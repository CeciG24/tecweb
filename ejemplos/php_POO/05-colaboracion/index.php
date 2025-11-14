<DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>05-Colaboracion</title>
    </head>

    <body>
        <?php
        require_once __DIR__ . '/Pagina.php';
        $pag1 = new Pagina('El atico del programador', 'El sotano del programador');
        for ($i = 0; $i < 15; $i++) {
            $pag1->insertar_cuerpo('Linea '.($i+1).' de la pagina');
        }
        $pag1->graficar();

        ?>
    </body>

    </html>
</DOCTYPE html>