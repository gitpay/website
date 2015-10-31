<?php

require_once 'vendor/autoload.php';
session_start();


$provider = new League\OAuth2\Client\Provider\Github([
    'clientId'          => '12d07069b2df91c0e52f',
    'clientSecret'      => '13c275356c9eadfb73d4a13f14b2539a5d6a74b7',
    'redirectUri'       => 'https://gitpay.org/oauth.php',

]);


$options = [
    'state' => rand(1, 999999999),
    'scope' => ['user','read:public_key', 'write:public_key'] // array or string
];


if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] != $_SESSION['oauth2state'])) {


    echo "state is : " . $_GET['state'];
    echo "session state is : " . $_SESSION['oauth2state'];

    //unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        //printf('Hello %s - testing - login functionality coming soon ...!', $user->getNickname());

        $_SESSION['loggedin'] = 'true';
        $_SESSION['login'] = $user->getNickname();
        $scheme = 'https://';
        header("Location: ". $scheme . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . $user->getNickname());
        exit();

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    // echo $token->getToken();
}

?>
