<?php

include_once('dbconfig.php');
$dsn = "mysql:host=$host;dbname=$db";
$dbh = new PDO($dsn, $username, $password);

try {

  // set the PDO error mode to exception
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = <<<EOSQL
  CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
    `login` VARCHAR(255) NOT NULL COMMENT '',
    `company` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '',
    `type` VARCHAR(255) NOT NULL DEFAULT 'USR' COMMENT '',
    `fake` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '',
    `deleted` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '',
    `long` DECIMAL(11,8) COMMENT '',
    `lat` DECIMAL(10,8) COMMENT '',
    `country_code` CHAR(3) COMMENT '',
    `state` VARCHAR(255) COMMENT '',
    `city` VARCHAR(255) COMMENT '',
    `location` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
    PRIMARY KEY (`id`)  COMMENT '')
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8;
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
  created_at TIMESTAMP NOT NULL,
  UNIQUE KEY `login` (`login`)
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


try {

  // set the PDO error mode to exception
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = <<<EOSQL
  CREATE TABLE ledger (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  uri VARCHAR(255) NOT NULL,
  balance INT(11) NULL,
  currency VARCHAR(255) NULL,
  wallet VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL
  ) ENGINE=InnoDB
EOSQL;


  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the ledger table.<br/>";
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
  CREATE TABLE wallet (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  uri VARCHAR(255) NOT NULL,
  codeRepository VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL
  ) ENGINE=InnoDB
EOSQL;


  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the wallet table.<br/>";
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
  CREATE TABLE `followers` (
    `follower_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ext_ref_id` varchar(24) NOT NULL DEFAULT '0',
    PRIMARY KEY (`follower_id`,`user_id`),
    KEY `follower_id` (`user_id`),
    CONSTRAINT `follower_fk1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
    CONSTRAINT `follower_fk2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8
EOSQL;


  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the followers table.<br/>";
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
  CREATE TABLE `publickeys` (
    `key_id` int(11) NOT NULL,
    `login` VARCHAR(255) NOT NULL,
    `key` VARCHAR(16000) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`key_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8
EOSQL;


  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the publickeys table.<br/>";
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
  CREATE TABLE accounts (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11) NOT NULL,
  predicate VARCHAR(255) NOT NULL,
  uri VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL,
  UNIQUE KEY `unique_index` (`user_id`,`uri`)
  ) ENGINE=InnoDB
EOSQL;

  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the accounts table.<br/>";
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
  CREATE TABLE preferences (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  webid VARCHAR(255) NOT NULL,
  active INT(11) NULL,
  created_at TIMESTAMP NOT NULL,
  UNIQUE KEY `webid` (`webid`)
  ) ENGINE=InnoDB
EOSQL;

  $r = $dbh->exec($sql);

  if ($r !== false){
    $msg =  "Tables are created successfully!<br/>";
  } else {
    $msg =  "Error creating the preferences table.<br/>";
  }

  // display the message
  if($msg !== '') {
    echo $msg;
  }

} catch (PDOException $e){
  echo $e->getMessage();
}
