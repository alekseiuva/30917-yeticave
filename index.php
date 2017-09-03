<?php
require_once 'functions.php';

$indexData = [
    'lot_time_remaining' => $lot_time_remaining,
    'categories' => $categories,
    'lots' => $lots,
];

$content = renderTemplate('./templates/index.php', $indexData);
$html = renderTemplate('./templates/layout.php', [
    'content' => $content,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
