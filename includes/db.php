<?php
ini_set('max_execution_time', 1800);
$db_username = 'root';
$db_password = '';
    $db = new PDO('mysql:host=localhost;dbname=issue_tracker', $db_username, $db_password, array(
        PDO::ATTR_PERSISTENT => true
    ));
