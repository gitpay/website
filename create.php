<?php

include_once('dbconfig.php');
$dsn = "mysql:host=$host;dbname=$db";
$dbh = new PDO($dsn, $username, $password);

try {

  // set the PDO error mode to exception
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = <<<EOSQL
  CREATE TABLE users (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(255) NOT NULL,
  name VARCHAR(255) NULL,
  company VARCHAR(255) NULL,
  location VARCHAR(255) NULL,
  email VARCHAR(255) NULL,
  avatar_url VARCHAR(255) NULL,
  blog VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL
  ) ENGINE=InnoDB
EOSQL;

  $r = $dbh->exec($sql);

  $msg = '';
  if ($r !== false) {
    $msg =  "Tables are created successfully!<br/>";
  } else{
    $msg =  "Error creating the users table.<br/>";
  }

  // display the message
  if($msg !== '') {
    echo $msg;
  }

} catch (PDOException $e){
  echo $e->getMessage();
}



try {

  // set the PDO error mode to exception
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = <<<EOSQL
  CREATE TABLE webid (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(255) NOT NULL,
  bitcoin VARCHAR(255) NULL,
  bitmark VARCHAR(255) NULL,
  preferredURI VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL
  ) ENGINE=InnoDB
EOSQL;


  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the webid table.<br/>";
  }

  // display the message
  if($msg !== '') {
    echo $msg;
  }

} catch (PDOException $e){
  echo $e->getMessage();
}


try {

  // set the PDO error mode to exception
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = <<<EOSQL
  CREATE TABLE followers (
  follower_id INT(11),
  user_id INT(11),
  created_at TIMESTAMP NOT NULL
  ) ENGINE=InnoDB
EOSQL;


  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the webid table.<br/>";
  }

  // display the message
  if($msg !== '') {
    echo $msg;
  }

} catch (PDOException $e){
  echo $e->getMessage();
}
