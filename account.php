<?php



$user = $_POST['user'];
$object = $_POST['object'];
$predicate = $_POST['predicate'];
$sig = $_POST['sig'];

include_once('dbconfig.php');

$conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// check sig
if ($user && $predicate && $object && $sig) {
  $key = exec("export HOME=/home/melvin ; git pay keys $user");
  $key = str_replace('[', '', $key);
  $key = str_replace(']', '', $key);

  $command = "git pay verify '<http://gitpay.org/$user#this> <$predicate> <$object> .' $sig $key";
  echo $command;
  //putenv("NODE_PATH=/usr/local/lib/node_modules:/usr/local/lib/node");
  $response = '';

  $response = exec($command);

  if ($response === 'true') {
    try {

      // sql to create table
      $sql = "INSERT INTO accounts (id, user_id, predicate, uri, created_at)  SELECT NULL, u.id, '$predicate', '$object', NULL  FROM users u where u.login = '$user';";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $user = $stmt->fetch();

    }
    catch(PDOException $e)
    {

      error_log($sql . " - " . $e->getMessage());
    }

  }

  exit;
}

?>

<!doctype html>
<!--
Material Design Lite
Copyright 2015 Google Inc. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

https://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Decentralized payments for github projects">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gitpay - Account</title>

  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="icon" sizes="192x192" href="images/touch/chrome-touch-icon-192x192.png">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Material Design Lite">
  <link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">

  <!-- Tile icon for Win8 (144x144 + tile color) -->
  <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
  <meta name="msapplication-TileColor" content="#3372DF">

  <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
  <!--
  <link rel="canonical" href="http://www.example.com/">
-->

<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="material.min.css">
<link rel="stylesheet" href="styles.css">
<style>
#view-source {
  position: fixed;
  display: block;
  right: 0;
  bottom: 0;
  margin-right: 40px;
  margin-bottom: 40px;
  z-index: 900;
}
</style>
</head>
<body>
  <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header mdl-color--white mdl-color--grey-100 mdl-color-text--grey-600">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">Account</span>
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
          <label class="mdl-button mdl-js-button mdl-button--icon" for="search">
            <i class="material-icons">search</i>
          </label>
          <div class="mdl-textfield__expandable-holder">
            <input class="mdl-textfield__input" type="text" id="search" />
            <label class="mdl-textfield__label" for="search">Enter your query...</label>
          </div>
        </div>
        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="hdrbtn">
          <i class="material-icons">more_vert</i>
        </button>
        <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right" for="hdrbtn">
          <li class="mdl-menu__item"><a class="mdl-color-text--blue-800" href="http://gitpay.org/">About</a></li>
          <li class="mdl-menu__item"><a class="mdl-color-text--blue-800" href="https://github.com/gitpay/code-of-conduct">Code of Conduct</a></li>
        </ul>
      </div>
    </header>
    <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
      <header class="demo-drawer-header">
        <div class="demo-avatar-dropdown">
          <div class="mdl-layout-spacer"></div>
        </div>
      </header>
      <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
      </nav>
    </div>
    <main class="mdl-layout__content mdl-color--grey-100">
      <div class="mdl-grid demo-content">
        <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
          <h3>Add Account Details</h3>
        </div>
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">

          <!-- Simple Textfield -->
          <form method="POST" action="account.php">
            <div class="mdl-textfield mdl-js-textfield textfield-demo">
              <input class="mdl-textfield__input" type="text" name="user" id="user" />
              <label class="mdl-textfield__label" for="user">Login</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield textfield-demo">
              <input class="mdl-textfield__input" type="text" name="predicate" id="predicate" />
              <label class="mdl-textfield__label" for="predicate">Type</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield textfield-demo">
              <input class="mdl-textfield__input" type="text" name="object" id="object" />
              <label class="mdl-textfield__label" for="object">Value</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield textfield-demo">
              <input class="mdl-textfield__input" type="text" name="sig" id="sig" />
              <label class="mdl-textfield__label" for="sig">signature</label>
            </div>
            <!-- Raised button with ripple -->
            <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
              Submit
            </button>
          </form>


        </div>
        <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
          <div class="demo-separator mdl-cell--1-col"></div>
        </div>
      </div>
    </main>
  </div>
  <script src="material.min.js"></script>
</body>
</html>
