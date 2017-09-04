<?php
require_once 'functions.php';
require_once 'data/lots.php';
require_once 'data/bets.php';

if (isset($_GET['id'])) {
    $lotId = $_GET['id'];
    $lot = array_key_exists($lotId, $lots) ? $lots[$lotId] : null;
}

if (isset($lot)) {
    $lotContent = renderTemplate('./templates/lot.php', [
        'bets' => $bets,
        'lot' => $lot,
    ]);
} else {
    http_response_code(404);
    $lotContent = renderTemplate('./templates/404.php', []);
}

$html = renderTemplate('./templates/layout.php', [
    'content' => $lotContent,
    'title' => 'DC Ply Mens 2016/2017 Snowboard',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
