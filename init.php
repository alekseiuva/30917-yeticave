<?php
$previous = error_reporting(0);
$connection = mysqli_connect('localhost', 'root', 'mowlmokm', 'yeticave');

if($connection == false) {
    error_reporting(0);
    http_response_code(404);
    $errorPage = renderTemplate('./templates/error.php', [
        'error_msg' => mysqli_connect_error(),
    ]);
    $html = renderTemplate('./templates/layout.php', [
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

error_reporting($previous);

?>
