<?php
require '../lib/Database.class.php';
$id = $_POST['id'];
$changeType = $_POST['changeType'];
$amount = $_POST['oldamount'];
if (!is_numeric($id) || $id < 1 || !is_numeric($amount) || $amount < 0 || !in_array($changeType, ['add', 'clear'])) {
    echo json_encode(['result' => false]);
    exit();
}

$where['numero'] = $id;
if ($changeType == 'add') {
    $change['orderamount'] = $amount + 1;
} else if ($changeType == 'clear' && $amount > 0) {
    $change['orderamount'] = $amount - 1;
}
if (!empty($change)) {
    $db = new Database();
    $db->update($change, 'alko_prices', $where);
}
echo json_encode(['result' => true]);
exit();