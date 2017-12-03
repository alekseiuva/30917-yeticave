<?php
require_once 'functions.php';
require_once 'mysql_helper.php';
require_once 'init.php';
require_once 'data.php';

$userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
$myBets = selectData($connection, 'SELECT * FROM bet JOIN lot ON bet.lot_id = lot.id WHERE user_id = ?', [ $userId ]);
$lots = selectData($connection, 'SELECT * FROM lot');

$lotContent = renderTemplate('./templates/my-lots.php', [
    'bets' => $myBets,
    'lots' => $lots,
    'user_id' => $userId,
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

if (isset($_SESSION['user'])) {
    print($html);
} else {
    header('Location: /login.php');
}

?>
