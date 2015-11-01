<?php

// includes
require_once 'vendor/autoload.php';
@include_once('server.php');
include_once('dbconfig.php');
include_once('github.php');
include_once('functions.php');



// query parameters
if ((isset($_REQUEST['user'])) && (!empty($_REQUEST['user']))) {
  $nick= $_REQUEST['user'];
} else {
  $nick = 'deiu';
}





// init
$throttled = false;
$uri = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


// DB
$fallbackdb = 'ghtorrent';
$conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$connfb = new PDO("mysql:host=$host;dbname=$fallbackdb", $username, $password);
$connfb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// process forms
if ((isset($_REQUEST['bitcoin'])) && (!empty($_REQUEST['bitcoin']))) {
  addBitcoin($_REQUEST['bitcoin'], $conn);
}


// Main
$user  = getUser($nick, $conn);
$webid = getWebID($nick, $conn);


if (!$user) {

  try {
    $user  = getUser($nick, null, $client);
  }  catch(Exception $e) {
    $throttled = true;
    $user  = getUser($nick, $connfb);

    if (!$user) {
      send503($nick);
      exit;
    }
  }

  insertUser($user, $nick, $conn);

}


// followers
try {
  $users = getFollowers($nick, null, $client);
  insertFollowers($users, $user['id'], $conn);

} catch(Exception $e) {
  $throttled = true;
  error_log( "<br>" . $e->getMessage());
}

if (!$users) {
  $users = getFollowers($nick, $connfb);
}



// keys
try {
  $keys = getKeys($nick, null, $client);
} catch(Exception $e) {
  $throttled = true;
  error_log($e->getMessage());

  $keys = getKeys($nick, $conn);

}


// turtle
$rank          = getRank($users);
$ledger        = getLedger($webid, $conn);
$project       = getProject($ledger);
$main          = 'http://gitpay.org/' . $user['login'] . '#this';
$githubaccount = 'http://github.com/' . $user['login'];
if ($webid && $webid['bitcoin']) {
  $bitcoin = $webid['bitcoin'];
}
if ($webid && $webid['preferredURI']) {
  $preferredURI = $webid['preferredURI'];
}

$turtle = getTurtle($user, $webid, $users, $keys);
insertKeys($keys, $nick, $conn);
writeTurtle();


if ( !empty($_SESSION['login']) ) {
  activateUser('https://gitpay.org/' . $_SESSION['login'] . '#this', $conn);
}

$active = getActive('https://gitpay.org/' . $nick . '#this', $conn);

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
  <title>Gitpay - <?php echo $user['login'] ?></title>

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
<link rel="stylesheet" href="auth-buttons.css">
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
        <span class="mdl-layout-title"><?php echo $user['login'] ?></span>
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
        <img src="<?php if(isset($user['avatar_url'])) { echo $user['avatar_url']; }else { echo 'https://avatars.githubusercontent.com/u/58120?v=3'; } ?>" class="demo-avatar">
          <div class="demo-avatar-dropdown">
            <span><?php if(isset($user['name'])) {echo $user['name'];} ?></span>
            <div class="mdl-layout-spacer"></div>
            <button id="btn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
              <i class="material-icons">arrow_drop_down</i>
            </button>
            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="btn">
              <li class="mdl-menu__item"><?php if(isset($user['created_at'])) {echo "Since : " . $user['created_at'];} ?></li>
              </ul>
            </div>
          </header>
          <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
            <a class="mdl-navigation__link" href="<?php if (!empty($_SESSION['login'])) echo $_SESSION['login'] ?>"><i class="mdl-color-text--blue-grey-400 material-icons">home</i>Home</a>
            <a class="mdl-navigation__link" target="_blank" href="https://melvincarvalho.gitbooks.io/gitpay/content/"><i class="mdl-color-text--blue-grey-400 material-icons">book</i>Documentation</a>
            <!--
            <a class="mdl-navigation__link" href="<?php echo $user['login'] ?>/activity/"><i class="mdl-color-text--blue-grey-400 material-icons">people</i>Social</a>
          -->
          <div class="mdl-layout-spacer"></div>
          <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons">help_outline</i></a>
        </nav>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid demo-content">
          <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
            <!--
            <svg fill="currentColor" width="200px" height="200px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--3-col-desktop">
            <use xlink:href="#piechart" mask="url(#piemask)" />
            <text x="0.5" y="0.5" font-family="Roboto" font-size="0.3" fill="#888" text-anchor="middle" dy="0.1"><?php echo $rank ?><tspan font-size="0.2" dy="-0.07">%</tspan></text>
          </svg>
          <h3>Gitpay Ranking <?php  if (isset($ledger) && $ledger['balance']) echo "<br><a class='mdl-color-text--blue-800' target='_blank' href='w/?walletURI=https:%2F%2Fgitpay.databox.me%2FPublic%2F.wallet%2Fgithub.com%2Flinkeddata%2FSoLiD%2Fwallet%23this&user=". urlencode($preferredURI) ."'>$ledger[balance] bits</a> - <a href='$project'>Project</a>" ; ?></h3>
        -->

        <?php if( isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'true' && $_SESSION['login'] === $nick  ) {
          echo "<h3>Welcome, " . $nick .  "<h3>";
        } else {
          if (!empty($active) && $active['active'] == 1 ) {
            echo "<h3>This account is active</h3>";
          } else {
            echo "<h3>This account has not yet been activated</h3>";
          }
        }
        ?>
      </div>

      <div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--8-col">
        <h3>Info</h3>
        <hr>

        <?php if( isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === 'true' && $_SESSION['login'] === $nick  ) {
          echo "<h4>Congratulations, you have logged in.  You have earnt one reputation point.  Account management and more <a target=\"_blank\" href=\"https://melvincarvalho.gitbooks.io/gitpay/content/\">features</a> coming soon.<h4><hr>";

          ?>
          <h3>Donations</h3>
          <h4>To set up donations add your bitcoin address to beneath</h4>
            <form action="" method="POST">
              <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="bitcoin" id="bitcoin" />
                <label class="mdl-textfield__label" for="bitcoin">bitcoin address</label>
              </div>
            </form>
          <hr>
          <?php

        } else {
          if (!empty($active) && $active['active'] == 1 ) {
          } else {
            ?>

            <h4>The page is a preview and generated from profile data that has been made public only.  All gitpay <a target="_blank" href="https://melvincarvalho.gitbooks.io/gitpay/content/">features</a> are opt in.  If this your account, and you wish to activate please:</h4>
            <div>
              <p><a class="btn-auth btn-github large" href="oauth.php">Sign in with <b>GitHub</b></a></p>
            </div>
            <hr>

            <?php
          }
        }
        ?>



        <?php
        if (!empty($active) && $active['active'] == 1 ) {
          $rank++;
          ?>
          <div class="demo-charts mdl-color--white mdl-shadow--2dp mdl-cell mdl-cell--12-col mdl-grid">
            <svg fill="currentColor" width="200px" height="200px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--3-col-desktop">
              <use xlink:href="#piechart" mask="url(#piemask)" />
              <text x="0.5" y="0.5" font-family="Roboto" font-size="0.3" fill="#888" text-anchor="middle" dy="0.1"><?php echo $rank ?><tspan font-size="0.2" dy="-0.07">%</tspan></text>
            </svg>
            <h3>Gitpay <a target="_blank" href="https://melvincarvalho.gitbooks.io/gitpay/content/chapter5.html">Reputation</a></h3>
            </div>
            <hr>

            <?php
          } else {
          }
          ?>


          <h3>Followers</h3>

          <?php
          for($i=0; $i<sizeof($users); $i++) {
            $login = $users[$i]['login'];
            echo "<div><a href='$login'>$login</a></div>";
          }
          ?>

        </div>



        <div class="demo-cards mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
          <div class="demo-updates mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
            <div class="mdl-card__title mdl-card--expand mdl-color--teal-300">
              <h2 class="mdl-card__title-text"><a class="mdl-color-text--blue-800" href="http://graphite.ecs.soton.ac.uk/browser/?uri=<?php echo $uri ?>">Linked Data</a></h2>
            </div>
            <?php if (!empty($main)) { ?>
              <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                Webid <a href="<?php echo $main ?>"><?php echo $main ?></a>
              </div>
              <?php } ?>

              <?php if (!empty($githubaccount)) { ?>
                <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                  Github <a rel="me" href="<?php echo $githubaccount ?>"><?php echo $githubaccount ?></a>
                </div>
                <?php } ?>

                <?php if (!empty($preferredURI)) { ?>
                  <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                    sameAs <a rel="me" href="<?php if (isset($preferredURI)) echo $preferredURI ?>"><?php if (isset($preferredURI))  echo $preferredURI ?></a>
                  </div>
                  <?php } ?>

                  <?php if (!empty($user['blog'])) { ?>
                    <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                      seeAlso <a rel="me" href="<?php if (isset($user['blog'])) { echo $user['blog']; } ?>"><?php  if (isset($user['blog'])) { echo $user['blog']; } ?></a>
                    </div>
                    <?php } ?>

                    <?php if (!empty($bitcoin)) { ?>
                      <div class="mdl-card__supporting-text mdl-color-text--grey-600">
                        bitcoin <a rel="me" href="<?php if (isset($bitcoin)) echo $bitcoin ?>"><?php if (isset($bitcoin)) echo fromBitcoinURI($bitcoin) ?></a>
                        <a target="_blank" href="https://blockchain.info/address/<?php if (isset($bitcoin)) echo fromBitcoinURI($bitcoin) ?>"><i class="mdl-color-text--blue-grey-400 material-icons">information</i></a>
                      </div>
                      <?php } ?>
                      <div class="mdl-card__actions mdl-card--border">
                        <a target="_blank" href="http://www.w3.org/DesignIssues/LinkedData.html" class="mdl-button mdl-js-button mdl-js-ripple-effect">Read More</a>
                      </div>
                    </div>


                    <div class="demo-separator mdl-cell--1-col"></div>
                    <div class="demo-options mdl-card mdl-color--deep-purple-500 mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--3-col-tablet mdl-cell--12-col-desktop">
                      <div class="mdl-card__supporting-text mdl-color-text--blue-grey-50">
                        <h3>Location</h3>
                        <ul>
                          <li>
                            <span><a class="mdl-color-text--white" target="_blank" href="http://www.geonames.org/search.html?q=<?php if (isset($user['location'])) { echo $user['location']; } ?>"><?php if (isset($user['location'])) { echo $user['location']; } ?></a></span>
                          </li>
                        </ul>
                      </div>
                      <div class="mdl-card__actions mdl-card--border">
                        <!-- <a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--blue-grey-50">Change location</a>
                        <div class="mdl-layout-spacer"></div>
                        <i class="material-icons">location_on</i>
                      -->
                    </div>
                  </div>

                  <div class="demo-separator mdl-cell--1-col"></div>
                  <div class="demo-options mdl-card mdl-color--deep-purple-500 mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--3-col-tablet mdl-cell--12-col-desktop">
                    <div class="mdl-card__supporting-text mdl-color-text--blue-grey-50">
                      <h3>Keygen -- Generate Key</h3>
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                      <form action="keygen.php" method="post">
                        <keygen name="key">
                          <input type="submit">
                        </form>
                      </div>
                    </div>


                  </div>
                </div>
              </main>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" style="position: fixed; left: -1000px; height: -1000px;">
              <defs>
                <mask id="piemask" maskContentUnits="objectBoundingBox">
                  <circle cx=0.5 cy=0.5 r=0.49 fill="white" />
                  <circle cx=0.5 cy=0.5 r=0.40 fill="black" />
                </mask>
                <g id="piechart">
                  <circle cx=0.5 cy=0.5 r=0.5 />
                  <path d="M 0.5 0.5 0.5 0 A 0.5 0.5 0 0 1 0.95 0.28 z" stroke="none" fill="rgba(255, 255, 255, 0.75)" />
                </g>
              </defs>
            </svg>


            <script src="material.min.js"></script>
          </body>
          </html>
