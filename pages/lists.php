<?php
include_once ('classes.php');
$cat = $_POST['cat'];
$pdo=Tools::connect();

$items=Item::GetItems($cat);
if($items==null)exit;

// выводим
foreach ($items as $item) {
    $item->Draw();
}
