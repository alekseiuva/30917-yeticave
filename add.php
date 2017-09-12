<?php
session_start();
require_once 'functions.php';
require_once 'data/bets.php';

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

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = finfo_file($finfo, $_FILES['image']['tmp_name']);
        $isTypeValid = in_array($fileType, $validFileTypes);
        $isSizeValid = $fileSize < 200000;

        if (!$isTypeValid) {
            $formErrors['image']  = 'Загрузите картинку в формате JPEG';
        }

        if (!$isSizeValid) {
            $formErrors['image']  = 'Максимальный размер файла – 200Кб';
        }

        if ($isTypeValid && $isSizeValid) {
            $filePath = __DIR__ . '/img/';
            $fileUrl = '/img/' . $fileName;
            move_uploaded_file($_FILES['image']['tmp_name'], $filePath . $fileName);
        }
    }
}

$lot = [
    'name' => $_POST['name'] ?? '',
    'description' => $_POST['description'] ?? '',
    'category' => $_POST['category'] ?? '',
    'price' => $_POST['price']  ?? '',
    'step' => $_POST['step'] ?? '',
    'date' => $_POST['date'] ?? '',
    'image' => $fileUrl
];

$lotTemplate = empty($formErrors) && $_SERVER['REQUEST_METHOD'] == 'POST' ?
    './templates/lot.php' :
    './templates/add-lot.php';

$formContent = renderTemplate($lotTemplate, [
    'formErrors' => $formErrors,
    'categories' => $categories,
    'pew' => $_FILES,
    'lot' => $lot,
    'bets' => $bets
]);

$html = renderTemplate('./templates/layout.php', [
    'content' => $formContent,
    'categories' => $categories,
    'title' => 'Add new lot',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

if (isset($_SESSION['user'])) {
    print($html);
} else {
    // header('HTTP/1.1 403 Forbidden');
    header('Location: /login.php');
}

?>
