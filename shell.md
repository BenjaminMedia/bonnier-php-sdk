# bonnier-php-sdk shell service

Service class for getting the shell.

#### Official documentation
http://mmm.dk/api/v2/documentation/external_headers

### Examples

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
$head = $shell->getHead(); // return head
$header = $shell->getHeader(); // return header
$banners = $shell->getBanners(); // return banners
$body = $shell->getEndTag(); // return end tag
$startTag = $shell->getStartTag(); // return start tag
$endTag = $shell->getBody(); // return body \stdClass
$logos = $shell->getLogos(); // return an array with a logo_standard and a logo_unicolor_white url
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