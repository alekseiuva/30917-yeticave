<?php
require_once 'functions.php';

$categories = selectData($connection, 'SELECT name FROM category');
$lots = selectData($connection, 'SELECT * FROM lot');
?>
