<?php

// Helper functions
function select($sql, $conn) {
  try {

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetch();
    return $res;

  }
  catch(PDOException $e)
  {
    error_log($sql . " - " . $e->getMessage());
  }

}

function selectAll($sql, $conn) {
  try {

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetchAll();
    return $res;

  }
  catch(PDOException $e)
  {
    error_log($sql . " - " . $e->getMessage());
  }

}

function insertUser($user, $nick, $conn) {
  try {

    // sql to create table
    if(!isset($user['avatar_url'])) {
      $avatar = "NULL";
    } else {
      $avatar = "'$user[avatar_url]'";
    }
    if(!isset($user['blog'])) {
      $blog = "NULL";
    } else {
      $blog = "'$user[blog]'";
    }
    if(!isset($user['company'])) {
      $company = "NULL";
    } else {
      $company = "'$user[company]'";
    }
    if(!isset($user['location'])) {
      $location = "NULL";
    } else {
      $location = "'$user[location]'";
    }
    if(!isset($user['email'])) {
      $email = "NULL";
    } else {
      $email = "'$user[email]'";
    }
    if(!isset($user['name'])) {
      $name = "NULL";
    } else {
      $name = "'$user[name]'";
    }
    $sql = "insert into users values ($user[id], '$nick', $name, $email, $company, $location, $avatar, $blog, NULL, DEFAULT, DEFAULT, DEFAULT, DEFAULT) ; ";
    error_log($sql);

    $stmt = $conn->prepare($sql);
    $stmt->execute();

  }
  catch(Exception $e)
  {
    error_log($sql . " - " . $e->getMessage());
  }

}


function send503($nick) {
  error_log('api error for user : ' . $nick);
  header('HTTP/1.1 503 Service Temporarily Unavailable');
  header('Status: 503 Service Temporarily Unavailable');
  header('Retry-After: 3600');//300 seconds
}

function getUser($nick, $conn = null, $client = null) {
  if ($conn) {
    $sql = "select * from users where login = '$nick' and deleted != 1; ";
    $user = select($sql, $conn);
  } else if ($client) {
    //throw new Exception('503 simulated');
    $user = $client->api('user')->show($nick);
  }
  return $user;
}

function getActive($nick, $conn) {
  $sql = "select * from preferences where webid = '$nick'; ";
  $active = select($sql, $conn);

  return $active;
}


function insertFollowers($users, $id, $conn) {
  for ($i=0; $i<sizeof($users); $i++) {
    $fid = $users[$i]['id'];
    $sql = "insert into followers values ($fid, $id, NULL, DEFAULT) ; ";
    //error_log($sql);
    $stmt = $conn->prepare($sql);
    try {
      $stmt->execute();
    } catch(Exception $e) {
      //error_log( $sql . " : " . $e->getMessage());
    }
  }
}

function activateUser($user, $conn) {

  $sql = "replace into preferences values (NULL, '$user', 1, NULL) ; ";
  //error_log($sql);
  $stmt = $conn->prepare($sql);
  try {
    $stmt->execute();
  } catch(Exception $e) {
    //error_log( $sql . " : " . $e->getMessage());
  }
}

function addBitcoin($bitcoin, $conn) {
  if (empty($_SESSION['login'])) {
    return;
  }
  $sql = "update webid set bitcoin = '$bitcoin' where login = '$_SESSION[login]' ; ";
  //error_log($sql);
  $stmt = $conn->prepare($sql);
  try {
    $stmt->execute();
  } catch(Exception $e) {
    //error_log( $sql . " : " . $e->getMessage());
  }
}

function getProject() {
  if (isset($ledger) && $ledger['codeRepository']) {
    $arr = split('/', $ledger['codeRepository']);
    $len = sizeof($arr);
    $project = $arr[$len-2] . '/' . $arr[$len-1];
    return $project;
  }
}

function getLedger($webid, $conn) {
  // get webid values
  if ($webid && $webid['preferredURI']) {
    $preferredURI = $webid['preferredURI'];

    try {
      // sql to create table
      $sql = "select l.*, w.codeRepository from ledger l inner join wallet w on w.uri = l.wallet where l.uri = '$preferredURI' ; ";

      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $ledger = $stmt->fetch();
      return $ledger;

    }
    catch(PDOException $e)
    {
      echo $sql . "<br>" . $e->getMessage();
    }

  }

}


function getRank($users) {
  $rank = sizeof($users) * 3;
  if ($rank > 100) {
    $rank = 100;
  }
  return $rank;
}


function getFollowers($nick, $conn = null, $client = null) {
  if ($conn) {
    $sql = "select u.id as id, u.login as login from followers f inner join users u on f.user_id = u.id where follower_id = $nick ; ";
    $followers = selectAll($sql, $conn);
  } else if ($client) {
    $followers = $client->api('user')->followers($nick);
  }
  return $followers;

}

function getKeys($nick, $conn = null, $client = null) {
  if ($conn) {
    $sql = "select key_id as id, `key` from publickeys where login = '$nick' ;";
    $keys = selectAll($sql, $conn);
  } else if ($client) {
    $keys = $client->api('user')->keys($nick);
  }
  return $keys;

}

function getWebID($nick, $conn) {
  $sql = "select * from webid where login = '$nick' ; ";
  $webid = select($sql, $conn);
  return $webid;
}


function insertKeys($keys, $nick, $conn) {
  for($i=0; $i<sizeof($keys); $i++) {
    $key = $keys[$i]['key'];
    $id = $keys[$i]['id'];

    try {

      if(!isset($user['name'])) {
        $name = "NULL";
      } else {
        $name = "'$user[name]'";
      }
      $sql = "insert into publickeys values ($id, '$nick', '$key', NULL) ; ";
      //error_log($sql);

      $stmt = $conn->prepare($sql);
      $stmt->execute();

    }
    catch(Exception $e)
    {
      //echo $sql . "<br>" . $e->getMessage();
    }


  }

}

function getTurtle($user, $webid, $users, $keys) {
  $turtle = "<#this> a <http://xmlns.com/foaf/0.1/Person> ;\n";

  if (isset($user['name'])) {
    $turtle .= "<http://xmlns.com/foaf/0.1/name> \"$user[name]\" ;\n";
  }


  if (isset($user['avatar_url'])) {
    $turtle .= "<http://xmlns.com/foaf/0.1/img> <$user[avatar_url]> ;\n";
  }

  $turtle .= "<http://xmlns.com/foaf/0.1/account> <https://github.com/$user[login]> .\n";

  if (isset($webid['preferredURI'])) {
    $preferredURI = $webid['preferredURI'];
  }
  if (isset($preferredURI)) {
    $turtle .= "<#this> <http://www.w3.org/2002/07/owl#sameAs> <$preferredURI> .\n";
  }

  if (isset($user['blog'])) {
    $turtle .= "<#this> <http://www.w3.org/2000/01/rdf-schema#seeAlso> <$user[blog]> .\n";
  }

  if (isset($webid['bitcoin'])) {
    $bitcoin = $webid['bitcoin'];
  }
  if (isset($bitcoin)) {
    $turtle .= "<#this> <https://w3id.org/cc#bitcoin> <$bitcoin> .\n";
  }

  for($i=0; $i<sizeof($users); $i++) {
    $follows = $users[$i]['login'];
    $turtle .= "<http://gitpay.org/$follows#this> <http://rdfs.org/sioc/ns#follows>  <#this> .\n";
  }

  for($i=0; $i<sizeof($keys); $i++) {
    $key = $keys[$i]['key'];
    $id = $keys[$i]['id'];

    $command = "./convert.sh '$key'";
    $modulus = shell_exec ( $command );

    // for gold strip off 00 for 2048 bit keys
    if (strlen($modulus) === 514 && strpos($modulus, '00') === 0) {
      $modulus = substr($modulus, 2);
    }


    $turtle .= "<#this> <http://www.w3.org/ns/auth/cert#key> <#$id> .\n";
    $turtle .= "<#$id> a <http://www.w3.org/ns/auth/cert#RSAPublicKey> ; <http://www.w3.org/ns/auth/cert#modulus> \"$modulus\"^^<http://www.w3.org/2001/XMLSchema#hexBinary> ; <http://www.w3.org/ns/auth/cert#exponent> \"65537\"^^<http://www.w3.org/2001/XMLSchema#integer> .\n";

  }
}

function writeTurtle() {
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

?>
