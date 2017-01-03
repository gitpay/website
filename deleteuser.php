<?php

// includes
require_once 'vendor/autoload.php';
@include_once('server.php');
include_once('dbconfig.php');
include_once('github.php');
include_once('functions.php');

// DB
$fallbackdb = 'ghtorrent';
$conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connfb = new PDO("mysql:host=$host;dbname=gitpay", $username, $password);
$connfb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'true') {
	deleteUser($_SESSION['login'], $conn);
}

?>