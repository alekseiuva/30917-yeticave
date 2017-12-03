<?php
require_once 'init.php';

$select = "SELECT
bet.lot_id as lot_id,
bet.user_id as user_id,
lot.name as lot_name,
user.name as user_name,
user.email as user_email,
MAX(bet.price) as bet_price
FROM bet
INNER JOIN lot ON bet.lot_id = lot.id
INNER JOIN user ON bet.user_id = user.id
WHERE lot.date_expires <= now() AND lot.winner_id IS NULL
GROUP BY lot_id, user_id";

$winners = selectData($connection, $select);

foreach($winners as $winner) {
    $lot_id = $winner['lot_id'];
    $lot_name = $winner['lot_name'];
    $user_id = $winner['user_id'];
    $user_name = $winner['user_name'];
    $user_email = $winner['user_name'];
    $result = execQuery($connection, 'UPDATE lot SET winner_id = ? WHERE id = ?', [$user_id, $lot_id]);
    // $result = execQuery($connection, 'UPDATE lot SET winner_id = NULL WHERE id = ?', [$lot]);

    if($result) {
        $message_content = renderTemplate('./templates/email.php', [
            'lot_id' => $lot_id,
            'lot_name' => $lot_name,
            'user_name' => $winner['user_name']
        ]);

        // Configure transport
        $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
            ->setUsername('doingsdone@mail.ru')
            ->setPassword('rds7BgcL');
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message('Ваша ставка победила'))
            // TODO: send it to winner
            ->setFrom(['mail@yeticave.academy' => 'Yeticave'])
            ->setTo(['mister.blblbl@gmail.com' => 'Сашун'])
            ->setBody($message_content);

        $mailer->send($message);
    }
}
?>

