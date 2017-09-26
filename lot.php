<?php
require_once 'init.php';
require_once 'data.php';

$formErrors = [];
$validationRules = [
    'price' => [
        'predicate' => 'checkBet',
        'errorMessage' => 'Невалидная сумма'
        ]
    ];
$isBetMade = false;
$userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

if (isset($_GET['id'])) {
    $lotId = $_GET['id'];

    $lot = selectData($connection, 'SELECT * FROM lot WHERE id = ?', [$lotId])[0];
    $priceStart = $lot['price_start'];
    $betStep = $lot['bet_step'];

    $bets = selectData($connection, 'SELECT date, price, name FROM bet JOIN user ON bet.user_id = user.id WHERE lot_id = ? ORDER BY bet.id DESC', [$lotId]);
    $userBets = isset($_SESSION['user']) ? selectData($connection, 'SELECT * FROM bet WHERE user_id = ? AND lot_id = ?', [$userId, $lotId]) : null;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach($_POST as $key => $value) {
        $predicate = $validationRules[$key]['predicate'];
        $msg = $validationRules[$key]['errorMessage'];
        $result = call_user_func($predicate, $value, $msg, [ 'price_start' => $priceStart, 'bet_step' => $betStep ]);

        if(!$result['isValid']) {
            $formErrors[$key] = $result['errorMessage'];
        }
    }

    if (empty($formErrors)) {
        $betInfo = [
            'lot_id' => $lotId,
            'user_id' => $userId,
            'price' => $_POST['price'],
            'date' => gmdate("Y-m-d H:i:s", time()),
        ];

        $betId = insertData($connection, 'bet', $betInfo);

        if ($betId) {
            header("Location: /my-lots.php");
        }
    }
}

if (isset($lot)) {
    $lotContent = renderTemplate('./templates/lot.php', [
        'lot' => $lot,
        'lotId' => $lotId,
        'categories' => $categories,
        'formErrors' => $formErrors,
        'bets' => $bets,
        'bet_permited' => $is_auth && $lot['author_id'] != $userId && count($userBets) == 0
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
