<?php
include_once(dirname(__DIR__).'/bootstrap.php');

// This file is designed for testing the WA login system

$service = new \Bonnier\Admin\ServiceOAuth(getenv('USER_AUTH_APP_ID'), getenv('USER_AUTH_SECRET'), getenv('USER_AUTH_ENDPOINT'));

$protocol = strpos('HTTP', getenv('SERVER_PROTOCOL')) === false ? 'http://' : 'https://';
$host = $protocol.getenv('HTTP_HOST');

if(!isset($_COOKIE['wa_token'])) {
    if(isset($_GET['code'])) {
        $service->setGrantToken($host, $_GET['code']);
        setcookie('wa_token', $service->getAccessToken(), time() + (10 * 365 * 24 * 60 * 60), '/');
    }
} else {
    $service->setAccessToken($_COOKIE['wa_token']);
}

$user = $service->getUser();
$userRoleList = $service->getUserRoleList();

if(!$user) {
    // If the user is not logged in, we redirect to the login screen.
    header("Location: ".$service->getLoginUrl($host, 'users'));
}
else {
    die(var_dump($user, $userRoleList));
}
