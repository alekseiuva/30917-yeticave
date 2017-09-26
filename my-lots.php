<?php
session_start();
require_once 'functions.php';
require_once 'mysql_helper.php';
require_once 'init.php';
require_once 'data.php';

$myBets = selectData($connection, 'SELECT * FROM bet JOIN lot ON bet.lot_id = lot.id;');
$lots = selectData($connection, 'SELECT * FROM lot');

$lotContent = renderTemplate('./templates/my-lots.php', [
    'bets' => $myBets,
    'lots' => $lots,
    'categories' => $categories
]);

$html = renderTemplate('./templates/layout.php', [
    'categories' => $categories,
    'content' => $lotContent,
    'title' => 'Мои ставки',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
