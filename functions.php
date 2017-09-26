<?php
require_once 'mysql_helper.php';

/**
* Форматирование времени
*
* @param $id
* @param $categories
* @return string – отформатированное время до окончания ставки
**/
function getCategoryName($id, $categories) {
    foreach ($categories as $category) {
        if ($category['id'] == $id) {
            return htmlspecialchars($category['name']);
        }
    }
}

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
function formatTime($ts) {
    $unixSeconds = strtotime($ts);
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
* @param $msg – сообщение об ошибке
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
* @param $msg – сообщение об ошибке
* @param $categories – массив категорий
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
* @param $msg – сообщение об ошибке
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
* Валидация ставки
*
* @param $value – значение поля
* @param $msg – сообщение об ошибке
* @param $categories – массив опций
* @return array – массив с полями isValid, errorMessage
**/
function checkBet($value, $msg, $options) {
    $isNumber = checkNumber($value, $msg);

    if ($isNumber['isValid']) {
        $isEnough = $value >= ($options['price_start'] + $options['bet_step']);
        return [
            'isValid' => $isEnough,
            'errorMessage' => $isEnough ? '' : $msg
        ];
    }
    return $isNumber;
}

/**
* Валидация числовых даты
*
* @param $value – значение поля
* @param $msg – сообщение об ошибке
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

/**
* Валидация поля email
*
* @param $value – значение поля
* @param $msg – сообщение об ошибке
* @return array – массив с полями isValid, errorMessage
**/
function checkEmailIsValid($value, $msg) {
    $isValid = filter_var($value, FILTER_VALIDATE_EMAIL);
    return [
        'isValid' => boolval($isValid),
        'errorMessage' => boolval($isValid) ? '' : $msg
    ];
}

/**
* Валидация поля email
*
* @param $value – значение поля
* @param $msg – сообщение об ошибке
* @return array – массив с полями isValid, errorMessage
**/
function checkEmailExists($value, $msg, $link) {
    $user = searchUserByEmail($value, $link);
    $isNotUsed = count($user) == 0;
    return [
        'isValid' => $isNotUsed,
        'errorMessage' => $isNotUsed ? '' : $msg
    ];
}

/**
* Валидация поля email
*
* @param $value – значение поля
* @param $msg – сообщение об ошибке
* @param $link – ресурс соединения с бд
* @return array – массив с полями isValid, errorMessage
**/
function checkEmail($value, $msg, $link) {
    $emptyCheck = checkRequired($value, 'Укажите email');
    $validityCheck = checkEmailIsValid($value, 'Укажите валидный email');
    $existanceCheck = checkEmailExists($value, $msg, $link);
    $result = [
        'isValid' => true,
        'errorMessage' => ''
    ];

    foreach ([$emptyCheck, $validityCheck, $existanceCheck] as $check) {
        if (!$check['isValid']) {
            $result = [
                'isValid' => $check['isValid'],
                'errorMessage' => $check['errorMessage']
            ];
        }
    }

    return $result;
}

function searchUserByEmail($email, $link) {
    return selectData($link, 'SELECT * FROM user WHERE email = ?', [$email]);
}

function saveFile($file) {
    $validFileTypes = ['image/jpeg', 'image/png'];

    if ($file['error'] == 0) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileType = finfo_file($finfo, $file['tmp_name']);
        $isTypeValid = in_array($fileType, $validFileTypes);
        $isSizeValid = $fileSize < 200000;

        if (!$isTypeValid) {
            return [
                'isValid' => false,
                'errorMessage' => 'Загрузите картинку в формате JPEG'
            ];
        }

        if (!$isSizeValid) {
            return [
                'isValid' => false,
                'errorMessage' => 'Максимальный размер файла – 200Кб'
            ];
        }

        if ($isTypeValid && $isSizeValid) {
            $filePath = __DIR__ . '/img/';
            $fileUrl = '/img/' . $fileName;
            move_uploaded_file($file['tmp_name'], $filePath . $fileName);

            return [
                'isValid' => true,
                'fileUrl' => $fileUrl
            ];
        }
    }
}
?>
