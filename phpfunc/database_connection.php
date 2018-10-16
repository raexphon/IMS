<?php
//database_connection.php
$dsn = 'mysql:dbname=fbinv;host=localhost';
$user = 'ism';
$password = 'ism';

try {
    $connect = new PDO($dsn, $user, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// session_start();
session_start();
$params = session_get_cookie_params();
setcookie("PHPSESSID", session_id(), 0, $params["path"], $params["domain"],
    true,  // this is the secure flag you need to set. Default is false.
    true  // this is the httpOnly flag you need to set
);
?>
