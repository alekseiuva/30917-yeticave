<?php
require_once 'init.php';
require_once 'data.php';

$formErrors = [];
$validationRules = [
    'email' => [
        'predicate' => 'checkRequired',
        'errorMessage' => 'Введите e-mail'
    ],
    'password' => [
        'predicate' => 'checkRequired',
        'errorMessage' => 'Введите пароль'
    ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    foreach($_POST as $key => $value) {
        $predicate = $validationRules[$key]['predicate'];
        $msg = $validationRules[$key]['errorMessage'];
        $result = call_user_func($predicate, $value, $msg);

        if(!$result['isValid']) {
            $formErrors[$key] = $result['errorMessage'];
        }
    }

    if (count($formErrors) === 0) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = searchUserByEmail($email, $connection);

        if (!empty($user) && password_verify($password, $user[0]['password'])) {
            $_SESSION['user'] = $user[0];
            header('Location: /index.php');
        } else {
            $formErrors['password'] = 'Вы ввели неверный пароль';
        }
    }
}

$content = renderTemplate('./templates/login.php', [
    'values' => $_POST,
    'formErrors' => $formErrors,
    'categories' => $categories
]);
$html = renderTemplate('./templates/layout.php', [
    'title' => 'Вход в личный кабинет',
    'categories' => $categories,
    'content' => $content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
