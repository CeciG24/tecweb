<?php

namespace App;

require_once __DIR__ . '/myapi/Products.php';

$products = new Products();

$products->search($_POST['search'] ?? '');

echo $products->getData();
?>