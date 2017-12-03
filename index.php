<?php
require_once 'init.php';
require_once 'data.php';
require_once 'getwinner.php';

$page_size = 3;
$total_items = selectData($connection, 'SELECT COUNT(*) as count FROM lot');
$curr_page = isset($_GET['page']) ? $_GET['page'] : 1;
$total_pages = ceil($total_items[0]['count'] / $page_size);
$offset = ($curr_page - 1) * $page_size;

$lots = selectData($connection, 'SELECT * FROM lot LIMIT ? OFFSET ?', [$page_size, $offset]);

$indexData = [
    'categories' => $categories,
    'lots' => $lots,
    'curr_page' => $curr_page,
    'total_pages' => $total_pages,
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
