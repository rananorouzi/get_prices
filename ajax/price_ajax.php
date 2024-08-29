<?php
require '../lib/Prices.class.php';
$page = $_POST['page'];
if (!is_numeric($page) || $page < 1) {
    $page = 1;
}

$priceObj = new Prices();
$alkoData = $priceObj->getAllFromTable('alko_prices', $page, 50);
if (is_array($alkoData)) {
    ob_start();
    include '../layouts/data_table.php';
    $buffer = ob_get_clean();
    echo json_encode(['result' => true, 'html' => $buffer]);
} else {
    echo json_encode(['result' => false]);
}
exit();
