# bonnier-php-sdk - Bonnier admin
Service for providing authentication through the Bonnier administration.

#### Official documentation
http://mmm.dk/api/documentation/user_login_flow

### Examples

#### Basic OAuth example

Creates a new instance of the ```OAuth``` and provide your ```appId``` and ```appSecret```.

```php
// The url which the user will be redirected to
$redirectUrl = 'https://example.com';

$bonnierAdmin = new \Bonnier\Admin\OAuth($appId, $appSecret);

if(!isset($_COOKIE['token'])) {
    if(isset($_GET['code'])) {
        $bonnierAdmin->setGrantToken($redirectUrl, $_GET['code']);
        setcookie('token', $bonnierAdmin->getAccessToken(), time() * 60, '/');
    }
} else {
    $bonnierAdmin->setAccessToken($_COOKIE['token']);
}

$user = $bonnierAdmin->getUser();

if(!$user) {
    // If the user is not logged in, we redirect to the login screen.
    header('location: ' . $bonnierAdmin->getLoginUrl($redirectUrl));
}

// Contains the currently active user
var_dump($user);
```