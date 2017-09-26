<?php
require_once 'init.php';
require_once 'data.php';

$formErrors = [];
$fileUrl = null;
$validationRules = [
    'name' => [
        'predicate' => 'checkRequired',
        'errorMessage' => 'Заполните это поле'
    ],
    'category' => [
        'predicate' => 'checkCategory',
        'errorMessage' => 'Выберите категорию'
    ],
    'description' => [
        'predicate' => 'checkRequired',
        'errorMessage' => 'Заполните это поле'
    ],
    'price' => [
        'predicate' => 'checkNumber',
        'errorMessage' => 'Введите значние больше нуля'
    ],
    'step' => [
        'predicate' => 'checkNumber',
        'errorMessage' => 'Введите значние больше нуля'
    ],
    'date' => [
        'predicate' => 'checkExpDate',
        'errorMessage' => 'Введите валидную дату'
    ]
];
$validFileTypes = ['image/jpeg', 'image/png'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST as $key => $value) {
        $predicate = $validationRules[$key]['predicate'];
        $msg = $validationRules[$key]['errorMessage'];

        $result = $key === 'category' ?
            call_user_func($predicate, $value, $msg, $categories) :
            call_user_func($predicate, $value, $msg);

        if(!$result['isValid']) {
            $formErrors[$key] = $result['errorMessage'];
        }
    }

    $fileInfo = saveFile($_FILES['image']);
    if ($fileInfo['isValid']) {
        $fileUrl = $fileInfo['fileUrl'];
    } else {
        $formErrors['image']  = $fileInfo['errorMessage'];
    }
}

$lot = [
    'name' => $_POST['name'] ?? '',
    'description' => $_POST['description'] ?? '',
    'category_id' => $_POST['category'] ?? '',
    'price_start' => $_POST['price']  ?? '',
    'bet_step' => $_POST['step'] ?? '',
    'date_start' => gmdate("Y-m-d H:i:s", time()),
    'date_expires' => $_POST['date'] ?? '',
    'image' => $fileUrl,
    'author_id' => isset($_SESSION['user']) ? $_SESSION['user']['id'] : '',
];

$formContent = renderTemplate('./templates/add-lot.php', [
    'formErrors' => $formErrors,
    'categories' => $categories,
    'lot' => $lot
]);

$html = renderTemplate('./templates/layout.php', [
    'content' => $formContent,
    'categories' => $categories,
    'title' => 'Add new lot',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user'])) {
        header('Location: /login.php');
    }

    if(empty($formErrors)) {
        foreach ($lot as $item) {
            if(empty($item)) {
                unset($item);
            }
        }
        $newId = insertData($connection, 'lot', $lot);

        if ($newId) {
            header('Location: /lot.php?id=' . $newId);
        } else {
            $errorPage = renderTemplate('./templates/error.php', [
                'error_msg' => 'id s' . var_dump($newId),
            ]);
            print($errorPage);
        }
    } else {
        print($html);
    }
} else {
    if (isset($_SESSION['user'])) {
        print($html);
    } else {
        header('Location: /login.php');
    }
}

?>

