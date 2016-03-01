## Bonnier admin service
This section describes how to use the `ServiceOAuth` class to authenticate users against the bonnier user login api. The service uses OAuth as its foundation.

To use the the service you will need the to receive the following information from Bonnier, an `app_id` and a `app_secret` and finally a `api_enpoint` (The endpoint determines what user base you are authenticating against i.e. [frontend|backend] users).

Because OAuth only allows applications to authenticate users from known domains you must inform Bonnier employee's of the domains and protocols that your application will be using. Once Bonnier gets this information they will register your application and provide you with your credentials/endpoint needed for using the service.

Example app domains:
`http://myapp.dk` (Production), `http://staging-myapp.dk` (Staging), `http://local.myapp.dk` (Local development).

To test the actual login you should ask Bonnier to provide you with at test `email` and `password` combination.

Below is an example implementation using the `ServiceOAuth` class to authenticate WA backend users in your application:

``` php

$service = new \Bonnier\Admin\ServiceOAuth(getenv('USER_AUTH_APP_ID'), getenv('USER_AUTH_SECRET'), getenv('USER_AUTH_ENDPOINT'));
// The third parameter is the api endpoint this controls which api you are authenticating against.
// If not set it will default to the WA backend login endpoint: https://bonnier-admin.herokuapp.com/

$protocol = strpos('HTTP', getenv('SERVER_PROTOCOL')) === false ? 'http://' : 'https://';
// we build the redirect URI consisting of the currently used protocol and host
$host = $protocol.getenv('HTTP_HOST');

if(!isset($_COOKIE['wa_token'])) {
    if(isset($_GET['code'])) {
        // If a code has been set is means that the user has logged in and was redirected to our site with a ?code=somecode
        $service->setGrantToken($host, $_GET['code']);
        // we set a cookie with the access token in order to persist the user being logged in, even if they refresh the page.
        setcookie('wa_token', $service->getAccessToken(), time() + (10 * 365 * 24 * 60 * 60), '/');
    }
} else {
    // The user is already logged in we retrieve the access token and set it for later use
    $service->setAccessToken($_COOKIE['wa_token']);
}

// We try to get the user
$user = $service->getUser();

if(!$user) {
    // If the user is not logged in, we redirect to the login screen.
    header("Location: ".$service->getLoginUrl($host));
}
else {
    // the user is logged in and we successfully retrieved the information about them form the api
    die(var_dump($user));
}


```

---
#### For Bonnier employee's (no need to read if you are an external developer)
If you are an employee at Bonnier and need to register an application it can be done by going to [Bonnier OAuth Admin](https://bonnier-admin.herokuapp.com/admin) (For WA backend applications only).