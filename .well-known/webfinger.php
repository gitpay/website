<?php

$domain = 'gitpay.org';

$object = $_REQUEST['object'];

if (strpos($object, 'mailto:') === 0) {
  $object = substr($object, strlen('mailto:'));
}

include_once('../dbconfig.php');

$conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// check sig
if ($object) {
  try {

    // sql to create table
    $sql = "select * from users where email = '$object';";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $user = $stmt->fetch();

    $turtle = "<http://$domain/$user[login]#this> <http://xmlns.com/foaf/0.1/nick> \"$user[login]\" .\n";
    $turtle .= "<http://$domain/$user[login]#this> <http://xmlns.com/foaf/0.1/img> <$user[avatar_url]> .\n";
    $turtle .= "<http://$domain/$user[login]#this> <http://xmlns.com/foaf/0.1/name> \"$user[name]\" .\n";
    $turtle .= "<http://$domain/$user[login]#this> <http://www.w3.org/2000/01/rdf-schema#seeAlso> <$user[blog]> .\n";

    header('Access-Control-Allow-Origin : *');
    header("Vary: Accept");
    if (stristr($_SERVER["HTTP_ACCEPT"], "application/turtle")) {
      header("Content-Type: application/turtle");
      echo $turtle;
      exit;
    }
    if (stristr($_SERVER["HTTP_ACCEPT"], "text/turtle")) {
      header("Content-Type: text/turtle");
      echo $turtle;
      exit;
    }


  }
  catch(PDOException $e)
  {

    error_log($sql . " - " . $e->getMessage());
  }

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
  <base href="..">
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
          <h3>email lookup</h3>
        </div>
        <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">

          <!-- Simple Textfield -->
          <form method="GET" action=".well-known/webfinger">
            <div class="mdl-textfield mdl-js-textfield textfield-demo">
              <input class="mdl-textfield__input" type="text" name="object" id="object" />
              <label class="mdl-textfield__label" for="object">Email</label>
            </div>
            <!-- Raised button with ripple -->
            <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
              Submit
            </button>
          </form>

          <?php
          if (isset($user) && isset($user['login'])) {
            echo "<img height='128' width='128' src='$user[avatar_url]'/>";

            echo "name : $user[name]<br/>";
            echo "login : $user[login]<br/>";
            echo "url : $user[blog]<br/>";
          }
          ?>

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
