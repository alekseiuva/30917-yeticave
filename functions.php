<?php
$is_auth = isset($_SESSION['user']);
$user_name = $_SESSION['user']['name'] ?? null;
$user_avatar = $_SESSION['user']['avatar'] ?? 'img/no-avatar.jpg';

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// записать в эту переменную оставшееся время в этом формате (ЧЧ:ММ)
$lot_time_remaining = "00:00";

// временная метка для полночи следующего дня
$tomorrow = strtotime('tomorrow midnight');

// временная метка для настоящего времени
$now = time();

// оставшееся время до начала следующих суток
$seconds_remaining = $tomorrow - $now;
$lot_time_remaining = gmdate("H:i", $seconds_remaining);

$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

/**
* Форматирование времени
*
* @param $betStart – time stamp когда была сделана ставка
* @return string – время до окончания ставки
**/
function getRemaingTime($betStart) {
    $betEnd = $betStart + 24 * 60 * 60;
    $secondsRemaining = $betEnd - time();

    // return gmdate("H:i:s", $secondsRemaining);
    return $secondsRemaining;
}

/**
* Форматирование времени
*
* @param $unixSeconds – количество секунд
* @return string – отформатированная дата
**/
function formatTime($unixSeconds) {
    $now = time();
    $day = 24 * 60 * 60;
    $timePassed = $now - $unixSeconds;

    if ($timePassed > $day) {
        $date = date('d.m.Y в G:i', $unixSeconds);

        return $date;
    } else {
        $hours = floor($timePassed / (60 * 60));
        $minutes = floor($timePassed / 60);

        return $timePassed > (60 * 60) ? "${hours} часов назад" : "${minutes} минут назад";
    }
}

/**
* Валидация полей, обязательных для заполнения
*
* @param $value – значение поля
* @return array – массив с полями isValid, errorMessage
**/
function checkRequired($value, $msg) {
    $isValid = strlen($value) > 0;
    return [
        'isValid' => $isValid,
        'errorMessage' => $isValid ? '' : $msg
    ];
}

/**
* Валидация выбранной категории
*
* @param $value – значение поля
* @return array – массив с полями isValid, errorMessage
**/
function checkCategory($value, $msg, $categories) {
    $isValid = array_key_exists($value, $categories);
    return [
        'isValid' => $isValid,
        'errorMessage' => $isValid ? '' : $msg
    ];
}

/**
* Валидация числовых значений
*
* @param $value – значение поля
* @return array – массив с полями isValid, errorMessage
**/
function checkNumber($value, $msg) {
    $isValid = is_numeric($value) && $value > 0;
    return [
        'isValid' => $isValid,
        'errorMessage' => $isValid ? '' : $msg
    ];
}

/**
* Валидация числовых даты
*
* @param $value – значение поля
* @return array – массив с полями isValid, errorMessage
**/
function checkExpDate($value, $msg) {
    $now = time();
    $isValid = strtotime($value) > $now;
    return [
        'isValid' => $isValid,
        'errorMessage' => $isValid ? '' : $msg
    ];
}

/**
* Функция-шаблонизатор
*
* @param $template – путь к файлу шаблона
* @param $array – массив с данными шаблона
* @return string – сгенерированный HTML код шаблона в виде строки
**/

function renderTemplate($path, $args) {
    if (!file_exists($path)) {
        return '';
    }

    if (is_array($args)){
        extract($args);
    }

    ob_start();
    include $path;

    return ob_get_clean();
}

function searchUserByEmail($email, $users) {
    foreach ($users as $user) {
        if ($user['email'] == $email) {
            return $user;
        }
    }
}
?>
