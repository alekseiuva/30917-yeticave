<?php
require_once 'functions.php';

$indexData = [
    'bets' => $bets,
];

$lotContent = renderTemplate('./templates/lot.php', $indexData);
$html = renderTemplate('./templates/layout.php', [
    'content' => $lotContent,
    'title' => 'DC Ply Mens 2016/2017 Snowboard',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);

print($html);
?>
