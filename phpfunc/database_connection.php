<?php
//database_connection.php
$dsn = 'mysql:dbname=fbinv;host=127.0.0.1';
$user = 'root';
$password = '';

try {
    $connect = new PDO($dsn, $user, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

session_start();

?>