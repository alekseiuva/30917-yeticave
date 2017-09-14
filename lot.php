<?php
session_start();
require_once 'functions.php';
require_once 'data/lots.php';
require_once 'data/bets.php';

$formErrors = [];
$validationRules = [
    'price' => [
        'predicate' => 'checkNumber',
        'errorMessage' => 'Невалидная сумма'
    ]
];
$isBetMade = false;

if (isset($_GET['id'])) {
    $lotId = $_GET['id'];
    $lot = array_key_exists($lotId, $lots) ? $lots[$lotId] : null;
}

if (isset($_COOKIE['userBets'])) {
    $betCookie = json_decode($_COOKIE['userBets'], true);

    foreach ($betCookie as $bet) {
        if ($bet['lotId'] == $lotId) {
            $isBetMade = true;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST as $key => $value) {
        $predicate = $validationRules[$key]['predicate'];
        $msg = $validationRules[$key]['errorMessage'];
        $result = call_user_func($predicate, $value, $msg);

        if(!$result['isValid']) {
            $formErrors[$key] = $result['errorMessage'];
        }
    }

    if (count($formErrors) === 0) {
        $betInfo = [
            'lotId' => $lotId,
            'price' => $_POST['price'],
            'time' => time()
        ];

        $betCookie[] = $betInfo;
        setcookie('userBets', json_encode($betCookie));
        header("Location: /my-lots.php");
    }
}


if (isset($lot)) {
    $lotContent = renderTemplate('./templates/lot.php', [
        'categories' => $categories,
        'formErrors' => $formErrors,
        'bets' => $bets,
        'isBetMade' => $isBetMade,
        'is_auth' => $is_auth,
        'lotId' => $lotId,
        'lot' => $lot,
        ]);
    } else {
        http_response_code(404);
        $lotContent = renderTemplate('./templates/404.php', []);
    }

$html = renderTemplate('./templates/layout.php', [
    'categories' => $categories,
    'content' => $lotContent,
    'title' => isset($lot) ? $lot['name'] : '404. Страница не найдена',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
