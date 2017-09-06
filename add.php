<?php
require_once 'functions.php';

$formErrors = [];
$fileUrl = null;
$validationRules = [
    'lot-name' => 'checkRequired',
    'category' => 'checkRequired',
    'message' => 'checkRequired',
    'lot-rate' => 'checkNumber',
    'lot-step' => 'checkNumber',
    'lot-date' => 'checkExpDate'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST as $key => $value) {
        $result = call_user_func($validationRules[$key], $value);

        if(!$result['isValid']) {
            $formErrors[$key] = $result['errorMessage'];
        }
    }

    if (isset($_FILES['lot-image'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $fileName = $_FILES['lot-image']['name'];
        $fileSize = $_FILES['lot-image']['size'];
        $fileType = finfo_file($finfo, $_FILES['lot-image']['tmp_name']);
        $isTypeValid = $fileType === 'image/jpeg';
        $isSizeValid = $fileSize < 200000;

        if (!$isTypeValid) {
            return [
                'isValid' => false,
                'errorMessage' => 'Загрузите картинку в формате JPEG'
            ];
        }

        if (!$isSizeValid) {
            return [
                'isValid' => $false,
                'errorMessage' => 'Максимальный размер файла: 200Кб'
            ];
        }

        if ($isTypeValid && $isSizeValid) {
            $filePath = __DIR__ . '/img';
            $fileUrl = '/img' . $fileName;
            move_uploaded_file($_FILES['lot-image']['tmp_name'], $filePath . $fileName);
        }
    }
}

$formContent = renderTemplate('./templates/add-lot.php', [
    'formErrors' => $formErrors,
    'categories' => $categories,
    'lotCategory' => $_POST['category'] ?? '',
    'lotName' => $_POST['lot-name'] ?? '',
    'message' => $_POST['message'] ?? '',
    'lotRate' => $_POST['lot-rate'] ?? '',
    'lotStep' => $_POST['lot-step'] ?? '',
    'lotDate' => $_POST['lot-date'] ?? '',
    'lotImage' => $fileUrl
]);

$html = renderTemplate('./templates/layout.php', [
    'content' => $formContent,
    'categories' => $categories,
    'title' => 'Add new lot',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
