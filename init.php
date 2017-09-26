<?php
session_start();
require_once 'functions.php';
require_once 'mysql_helper.php';

$is_auth = isset($_SESSION['user']);
$user_name = $_SESSION['user']['name'] ?? null;
$user_avatar = $_SESSION['user']['avatar'] ?? 'img/no-avatar.jpg';

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

$previous = error_reporting(0);
$connection = mysqli_connect('localhost', 'root', 'mowlmokm', 'yeticave');

if($connection == false) {
    error_reporting(0);
    http_response_code(404);
    $errorPage = renderTemplate('./templates/error.php', [
        'error_msg' => mysqli_connect_error(),
    ]);
    $html = renderTemplate('./templates/layout.php', [
        // FIXME: no db – no category
        'categories' => $categories,
        'content' => $errorPage,
        'title' => 'Ошибка',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar,
    ]);

    print($html);
    die();
}

mysqli_set_charset($connection, 'utf8');
error_reporting($previous);

?>
