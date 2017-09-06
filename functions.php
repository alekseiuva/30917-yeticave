<?php
$is_auth = (bool) rand(0, 1);
$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

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
function checkRequired($value) {
    $isValid = strlen($value) > 0;
    return [
        'isValid' => $isValid,
        'errorMessage' => $isValid ? '' : 'Заполните это поле'
    ];
}

/**
* Валидация числовых значений
*
* @param $value – значение поля
* @return array – массив с полями isValid, errorMessage
**/
function checkNumber($value) {
    $isValid = $value > 0;
    return [
        'isValid' => $isValid,
        'errorMessage' => $isValid ? '' : 'Введите значние больше нуля'
    ];
}

/**
* Валидация числовых даты
*
* @param $value – значение поля
* @return array – массив с полями isValid, errorMessage
**/
function checkExpDate($value) {
    $now = time();
    $isValid = strtotime($value) > $now;
    return [
        'isValid' => $isValid,
        'errorMessage' => $isValid ? '' : 'Введите валидную дату'
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
?>
