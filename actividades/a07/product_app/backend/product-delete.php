<?php

namespace App;

    require_once __DIR__ . '/myapi/Products.php';

    $products = new Products();
    $products->delete($_POST['id'] ?? '');

    echo $products->getData();
?>
