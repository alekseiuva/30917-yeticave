<?php
require_once 'functions.php';

$categories = selectData($connection, 'SELECT * FROM category');
?>
