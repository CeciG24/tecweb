<DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>03-Constructor</title>
    </head>

    <body>
        <?php
        use EJEMPLOS\POO\Cabecera2 as Cabecera;
        require_once __DIR__ .'/Cabecera2.php';
        
        $cab1=new Cabecera('El rincon del programador', 'center','https:/www.google.com');
        $cab1->graficar();
        ?>
    </body>

    </html>
</DOCTYPE html>