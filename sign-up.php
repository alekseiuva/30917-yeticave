<?php
require_once 'init.php';
require_once 'data.php';

$formErrors = [];
$validationRules = [
    'email' => [
        'predicate' => 'checkEmail',
        'errorMessage' => 'Аккаунт с таким адресом уже существует'
    ],
    'password' => [
        'predicate' => 'checkRequired',
        'errorMessage' => 'Введите пароль'
    ],
    'name' => [
        'predicate' => 'checkRequired',
        'errorMessage' => 'Укажите имя'
    ],
    'contacts_info' => [
        'predicate' => 'checkRequired',
        'errorMessage' => 'Укажите, как с вам связаться'
    ],
];
$fileUrl = null;
$dbResult = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($validationRules as $key => $value) {
        $submittedValue = $_POST[$key];
        $predicate =  $value['predicate'];
        $msg =  $value['errorMessage'];

        $result = $key === 'email' ?
        call_user_func($predicate, $submittedValue, $msg, $connection) :
        call_user_func($predicate, $submittedValue, $msg);

        if(!$result['isValid']) {
            $formErrors[$key] = $result['errorMessage'];
        }
    }

    $fileInfo = saveFile($_FILES['avatar']);
    if ($fileInfo['isValid']) {
        $fileUrl = $fileInfo['fileUrl'];
    } else {
        $formErrors['avatar']  = $fileInfo['errorMessage'];
    }
}

$newUser = [
    'name' => $_POST['name'] ?? '',
    'email' => $_POST['email'] ?? '',
    'contacts_info' => $_POST['contacts_info'] ?? '',
    'avatar' => $fileUrl,
];


if (count($formErrors) == 0 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbResult = insertData($connection, 'user', [
        'registeration_date' => gmdate("Y-m-d H:i:s", time()),
        'email' => $_POST['email'],
        'name' => $_POST['name'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'contacts_info' => $_POST['contacts_info'],
        'avatar' => $fileUrl,
    ]);

    if ($dbResult) {
        header('Location: /login.php?new_user');
    } else {
        $content = renderTemplate('./templates/error.php', [
            'error_msg' => mysqli_connect_error(),
        ]);
    }
}

$content = renderTemplate('./templates/sign-up.php', [
    'categories' => $categories,
    'formErrors' => $formErrors,
    'newUser' => $newUser,
    'result' => $dbResult
]);

$html = renderTemplate('./templates/layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => 'Регистрация',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
