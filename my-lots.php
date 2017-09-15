<?php
session_start();
require_once 'functions.php';
require_once 'data/mybets.php';
require_once 'data/lots.php';

if (isset($_COOKIE['userBets'])) {
    $betCookie = json_decode($_COOKIE['userBets'], true);

    foreach($betCookie as $userBet) {
        $id = $userBet['lotId'];
        // TODO: replace with the latest bet if item is the same
        $myBets[] = [
            'item' => $lots[$id]['name'],
            'id' => $id,
            'image' => $lots[$id]['image'],
            'category' => $lots[$id]['category'],
            'price' => $userBet['price'],
            'ends' => getRemaingTime($userBet['time']),
            'ts' => $userBet['time'],
            'status' => 'finishing'
        ];
    }
}

usort($myBets, function ($item1, $item2) {
    return $item2['ts'] <=> $item1['ts'];
});

$lotContent = renderTemplate('./templates/my-lots.php', [
    'bets' => $myBets,
    'lots' => $lots,
    'categories' => $categories
]);

$html = renderTemplate('./templates/layout.php', [
    'categories' => $categories,
    'content' => $lotContent,
    'title' => 'Мои ставки',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
