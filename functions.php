<?php
require_once 'mysql_helper.php';
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
* @param $betEnd – дата окончания ставки
* @return string – отформатированное время до окончания ставки
**/
function formatRemaingTime($betEndStr) {
    $secondsRemaining = strtotime($betEndStr) - time();

    return gmdate("H:i:s", $secondsRemaining);
    // return $secondsRemaining;
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

function getLotId($name, $lots) {
    foreach ($lots as $key => $lot) {
        if ($lot['name'] == $name) {
            return $key;
        }
    }
}

/**
* Функция для получения данных
*
* @param $link – ресурс соединения
* @param $query – SQL-запрос с плейсхолдерами для всех переменных значений
* @param [$queryData]–  простой массив со всеми значениями для запроса.
* @return array – пустой массив или двумерный массив с данными
**/
function selectData($link, $query, $queryData = []) {
    $data = [];
    $stmt = db_get_prepare_stmt($link, $query, $queryData);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = $row;
        }
    }

    return $data;
}

function prepareValues(&$value, $key, $link) {
    if (is_string($value)) {
        $value = $key . ' = ' . '\'' . mysqli_real_escape_string($link, $value) . '\'' ;
    } else {
        $value = $key . ' = ' . $value;
    }
}

/**
* Функция для вставки данных
*
* @param $link – ресурс соединения
* @param $tableName – имя таблицы, в которую добавляются данные
* @param [$queryData]–  пассоциативный массив, где ключи - имена полей, а значения - значения полей таблицы
* @return (false | $id)
**/
function insertData($link, $tableName, $queryData) {
    array_walk($queryData, 'prepareValues', $link);
    $strValues = implode(', ', $queryData);
    $query = "INSERT INTO ${tableName} SET ${strValues};";
    $stmt = db_get_prepare_stmt($link, $query);
    $result = false;

    if ($stmt) {
        $result = mysqli_stmt_execute($stmt) ? intval(mysqli_insert_id($link)) : false;
    }

    return $result;
}

/**
* Функция для произвольного запроса
*
* @param $link – ресурс соединения
* @param $query – SQL-запрос с плейсхолдерами для всех переменных значений
* @param [$queryData]–  простой массив со всеми значениями для запроса.
* @return bool
**/
function execQuery($link, $query, $queryData = []) {
    $stmt = db_get_prepare_stmt($link, $query, $queryData);

    return $stmt ? mysqli_stmt_execute($stmt) : false;
}
?>
