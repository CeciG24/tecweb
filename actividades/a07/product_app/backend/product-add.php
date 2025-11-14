<?php

namespace App;

require_once __DIR__ . '/myapi/Products.php';

$products = new Products();
$products->add(json_decode(json_encode($_POST)));

echo $products->getData();
?>