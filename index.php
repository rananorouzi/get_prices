<!DOCTYPE html>
<html lang="fi" dir="rtl">
<head>
    <?php include 'layouts/meta.html'; ?>
    <title>Draivi Backend test</title>
</head><!--/head-->

<body>
    <?php require_once(__DIR__ . '\vendor\autoload.php');  ?>
    <button onclick="callAlkoDataInitial(1)">List</button> <button onclick="removeTable()">Empty</button>
    <div id="table_prices">
    </div>
</body>
</html>