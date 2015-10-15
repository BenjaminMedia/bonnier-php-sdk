# bonnier-php-sdk - Bonnier admin
Service for providing authentication through the Bonnier administration.

#### Official documentation
http://mmm.dk/api/documentation/user_login_flow

### Examples

#### Basic OAuth example

Creates a new instance of the ```\Bonnier\Admin\ServiceOAuth``` and provide the ```$appId``` and ```$appSecret``` valid for your application.

Remember to change the ```$redirectUrl``` so it matches the correct route for your application.

**NOTE: According to the documentation the ```redirectUrl``` is not required when setting the grant-token using the ```setGrantToken``` method. 
However, I werent able to make it work without - as it seems it is required by mistake or theres an error in the documentation.**

```php
// The url which the user will be redirected to
$redirectUrl = 'https://example.com';

// Create new instance of the OAuth service
$bonnierAdmin = new \Bonnier\Admin\ServiceOAuth($appId, $appSecret);

// If the token doesn't exist in the cookie, we continue...
if(!isset($_COOKIE['token'])) {

    // If the grant token (code parameter) is provided within the url, we fetch the access token.
    if(isset($_GET['code'])) {
        $bonnierAdmin->setGrantToken($redirectUrl, $_GET['code']);
        
        // ... and stores it in a cookie.
        setcookie('token', $bonnierAdmin->getAccessToken(), time() * 60, '/');
    }
} else {
    // The cookie already exists, so we set the valid access token.
    $bonnierAdmin->setAccessToken($_COOKIE['token']);
}

// Get the currently signed in user.
$user = $bonnierAdmin->getUser();

if(!$user) {
    // If the user is not logged in, we redirect to the login screen.
    header('location: ' . $bonnierAdmin->getLoginUrl($redirectUrl));
}

// Contains the currently active user
var_dump($user);
```
