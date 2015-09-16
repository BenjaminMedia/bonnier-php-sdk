# bonnier-php-sdk shell service

Service class for getting the shell.

#### Getting the shell

Creates a new instance of the ```ServiceShell``` and provide your ```username``` and ```secret```.

The call the ```get``` method with the domain that you want the Shell for - in this example we want the shell for ```staging.boligmagasinet.dk```.

The ```get``` method will return a ```\Bonnier\Shell\ShellResponse``` object.

```php
$service = new \Bonnier\Shell\ServiceShell($username, $password);

// Set javascript position example
$service->setJavascriptPosition(\Bonnier\Shell\ServiceShell::JS_POSITION_HEADER);

// Calls the service and returns a ShellResponse object
$shell = $service->get('staging.boligmagasinet.dk');

// Get the values
$head = $shell->getHead(); // returns header
$body = $shell->getBody(); // returns body
$banners = $shell->getBanners(); // return banners
$footer = $shell->getFooter(); // returns footer
$startTag = $shell->getStartTag(); // returns start tag
$endTag = $shell->getEndTag(); // returns end tag
```

#### Debugging the result

The ```\Bonnier\Shell\ShellResponse``` contains a original instance of the ```HttpResponse``` object. This allows you to get information about the request made.

**Example:**

```php
$service = new \Bonnier\Shell\ShellService($username, $password);
$shell = $service->get('staging.boligmagasinet.dk');

$info = $shell->getHttpResponse()->getInfo(); // Returns information about the request, http-code etc.
$response = $shell->getHttpResponse()->getResponse(); // Returns raw response
$httpCode = $shell->getHttpResponse()->getStatusCode(); // Returns http status code
$handle = $shell->getHttpResponse()->getHandle(); // Returns the curl handle
```