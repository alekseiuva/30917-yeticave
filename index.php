<?php
require_once 'init.php';
require_once 'data.php';

$lots = selectData($connection, 'SELECT * FROM lot');
$indexData = [
    'categories' => $categories,
    'lots' => $lots,
];

$content = renderTemplate('./templates/index.php', $indexData);
$html = renderTemplate('./templates/layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => 'Yeticave',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
