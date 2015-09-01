# bonnier-php-sdk
PHP SDK for communicating with the Bonnier search db webservice.

## Examples 
------------
Even though these examples are pretty rough, they should give you a basic understanding on how to use the index-db service. 
All related index-db classes extends from the ```\Bonnier\IndexDB\IndexSearchBase``` class - which contains the basic functionality for communicating with webservices using the index-search authentication.

#### Initialize service
```php
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);
```

#### Get single
```php
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');
```

#### Update single
```php
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

$single->title = 'Hello world';
$single->description = 'My new description';
$single->update(); // Updates the existing object with the new values form the webservice
```

#### Save single

You can also create a new item by calling the save method directly on the ```ServiceContent`` class. This requires you to add the secret and type:

```php
$item = new \Bonnier\Service\IndexDB\ServiceContent($username, $secret);
$item->title = 'Hello world'; // Magic method, similar to calling $item->row->title = 'Hello world';
$item->save();
```

If you already has a instance of ```ServiceContent``` you can also save on directly on this by providing a reference to your object:

```php
// Initialized somewhere in your code
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);

// Create the new object
$item = new stdClass();
$item->title = 'Min titel';
$item->app_content_id = '123123';
$item->description = 'My description';
$item->source_image = 'http://www.revert.dk/logo.png';
$item->content_type = 'test';
$item->created_at = date('d-m-Y', time());
$item->updated_at = date('d-m-Y', time());
$item->path = '/';
$item->active = TRUE;

// Call the save method on ServiceContent
$response = $service->save($item); // Returns new object with updated response from service
```

#### Get list, add query and apply filters
Get the results sets, query everything matching "hello", filter title by "myFilter" and content by "secondFilter". api() makes the final call to the webservice.

```php
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);

$results = $service->getCollection() // Get the queryable ServiceCollection object
->query('hello') // Get everything that matches "hello"
->filter('title', 'myFilter') // Filter "title" by "myFilter"
->filter('content', 'secondFilter') // Add as many filters as you like
->sort('title') // Sort by title
->order('asc') // Order results by ASC
->execute(); // Call the service and get the results, similar to calling api()
```

#### Advanced usage

Use DSL to apply even greater filters, by using the "setDsl" method on the \Bonnier\IndexDB\Service\Content\ContentResult class. 

Please refer to the Elasticsearch documentation and examples on how to use DSL:
https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html

```php
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);
$filter = array('body' => array('query' => 'match' => array('testField' => 'abc')));
$results = $service->getCollection()->dsl($filter)->execute();
```

#### Add custom parameters, headers, timeout - etc.

Switch between production and staging environment by using:

```php
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);
$service->setDevelopment(TRUE);
```

If you want to implement a parameter that is not implemented, this can be done by adding it to the request-object.
NOTE: Please use this with caution as this is something that should be implemented into the SDK itself if the functionality is missing.

```php
$content = new \Bonnier\IndexDB\ServiceContent($username, $secret);
$content->getService()->getRequest()->addPostData($key, $value);
$content->getService()->getRequest()->addHeader($value);
$content->getService()->getRequest()->setTimeout(1000) // Set timeout in ms
```

#### Debugging the output

The service will throw an ```ServiceException``` on error.

This class contains some methods which will help debug the error.

```
try {
	// Something here made the service explode
} catch(ServiceException $e) {
	$url = $e->getResponse()->getUrl(); // Returns the full URL that the service has called.
	$info = $e->getResponse()->getInfo(); // Returns information about the response, http/code, length etc, parameters, headers etc.
	$httpCode = $e->getResponse()->getStatusCode(); // Returns the http status code
	$rawResponse = $e->getResponse()->getResponse(); // Returns the raw response
	$handle = $e->getResponse()->getHandle(); // Returns curl handle
}
```

You also get the HttpResponse object when retrieving something from the service.

```php
$service = new \Bonnier\IndexDB\ServiceContent($username, $secret);
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

$httpResponse = $single->getResponse(); // Returns HttpResponse object (simular as the one above)
```