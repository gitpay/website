<?php

$user = $_REQUEST['user'];
$account = $_REQUEST['account'];
$sig = $_REQUEST['sig'];

include_once('dbconfig.php');

$conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


try {

  // sql to create table
  $sql = "INSERT INTO accounts (id, user_id, uri, created_at)  SELECT NULL, u.id, '$account', NULL  FROM users u where u.login = '$user';";

  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $user = $stmt->fetch();

}
catch(PDOException $e)
{

  error_log($sql . " - " . $e->getMessage());
}
