<?php

namespace App;

require_once __DIR__ . '/myapi/Products.php';

$products = new Products();
$products->list();

echo $products->getData();
?>