# bonnier-php-sdk IndexSearch examples
PHP SDK for communicating with the Bonnier search db webservice.

## Examples 
------------
These examples are pretty rough, but should give you a basic understanding on how to use the ìndex-search service. 

All related ```IndexSearch``` classes extends from the ```\Bonnier\RestItem``` class - which contains a ```RestBase``` related ```service``` property, that contains the basic functionality for communicating with webservices using the index-search authentication.

#### Service classes

| Service class             | Description                                                       |
| -------------             | -------------                                                     |
| ```ServiceContent```      | Service for retrieving content from index-search                  |
| ```ServiceContentType```  | Service for retrieving content types from index-search            |
| ```ServiceAuth```         | Auth class for checking authentication through index-search       |
| ```ServiceApplication```  | Application class for information about index-search applications |

#### Get single

This examples retrieves a single content item from IndexSearch with the id ```FDE455B92EEBC96F72F2447D6AD17C40```

```php
$service = new \Bonnier\IndexSearch\ServiceContent($username, $secret);
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');
```

#### Update single

This examples updates a translation with the id ```FDE455B92EEBC96F72F2447D6AD17C40``` with the new values defined in the properties.

```php
$service = new \Bonnier\IndexSearch\ServiceContent($username, $secret);
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

$single->title = 'Hello world';
$single->description = 'My new description';
$single->update(); // Updates the existing object with the new values form the webservice
```

#### Save single

This example will create a new instance of ```ServiceContent``` and save it to index-search.

```php
$item = new \Bonnier\IndexSearch\ServiceContent($username, $secret);
$item->title = 'Min titel';
$item->app_content_id = '123123';
$item->description = 'My description';
$item->source_image = 'http://www.revert.dk/logo.png';
$item->content_type = 'test';
$item->created_at = date(DATE_WC3, time());
$item->updated_at = date(DATE_WC3, time());
$item->path = '/';
$item->locale = 'da_dk';
$item->content_url = 'http://www.revert.dk/12213-article.html';
$item->active = true;
$item->save();
```

You can also save an item by initiating a new instance of the ```RestItem``` class. This will requires you to add a service:

**Note: the method above is the correct way of saving if you are saving a specific type.**

```php
// Initialized somewhere in your code
$service = new \Bonnier\IndexSearch\ServiceContent($username, $secret);

$item = new \Bonnier\RestItem($service);
$item->title = 'Min titel';
$item->app_content_id = '123123';
$item->description = 'My description';
$item->source_image = 'http://www.revert.dk/logo.png';
$item->content_type = 'test';
$item->created_at = date('d-m-Y', time());
$item->updated_at = date('d-m-Y', time());
$item->path = '/';
$item->active = true;
$item->save();
``` 

#### Get list, add query and apply filters
Get the results sets, query everything matching "hello", filter title by "myFilter" and content by "secondFilter". api() makes the final call to the webservice.

```php
$service = new \Bonnier\IndexSearch\ServiceContent($username, $secret);

$results = $service->getCollection() // Get the queryable ServiceCollection object
->query('hello') // Get everything that matches "hello"
->filter('title', 'myFilter') // Filter "title" by "myFilter"
->filter('content', 'secondFilter') // Add as many filters as you like
->sort('title') // Sort by title
->order('asc') // Order results by ASC
->execute(); // Call the service and get the results, similar to calling api()
```

#### Advanced usage

Use DSL to apply even greater filters, by using the "setDsl" method on the ```\Bonnier\IndexSearch\Service\Content\ContentCollection``` class. 

Please refer to the Elasticsearch documentation and examples on how to use DSL:
https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html

```php
$service = new \Bonnier\IndexSearch\ServiceContent($username, $secret);
$filter = array('body' => array('query' => 'match' => array('testField' => 'abc')));
$results = $service->getCollection()->dsl($filter)->execute();
```

#### Add custom parameters, headers, timeout - etc.

Switch between production and staging environment by using:

```php
$service = new \Bonnier\IndexSearch\ServiceContent($username, $secret);
$service->setDevelopment(true);
```

If you want to implement a parameter that is not currently available, this can be done by adding it to the request-object.

**NOTE:** Please use this with caution as this is something that should be implemented into the SDK itself if the functionality is missing.

```php
$content = new \Bonnier\IndexSearch\ServiceContent($username, $secret);
$content->getService()->getHttpRequest()->addPostData($key, $value);
$content->getService()->getHttpRequest()->addHeader($value);
$content->getService()->getHttpRequest()->setTimeout(1000) // Set timeout in ms
```

#### Debugging the output

The service will throw an ```ServiceException``` on error.

This class contains some methods which will help debug the error.

```
try {
	// Something here made the service explode
} catch(ServiceException $e) {
	$url = $e->getHttpResponse()->getUrl(); // Returns the full URL that the service has called.
	$info = $e->getHttpResponse()->getInfo(); // Returns information about the response, http/code, length etc, parameters, headers etc.
	$httpCode = $e->getHttpResponse()->getStatusCode(); // Returns the http status code
	$rawResponse = $e->getHttpResponse()->getResponse(); // Returns the raw response
	$handle = $e->getHttpResponse()->getHandle(); // Returns curl handle
}
```

You also get the HttpResponse object when retrieving something from the service.

```php
$service = new \Bonnier\IndexSearch\ServiceContent($username, $secret);
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

$httpResponse = $single->getResponse(); // Returns HttpResponse object (simular as the one above)
```

#### Extending the functionality to customize your needs

If you want to return your own set of classes or add functionality to one of the service classes, this can be done using the example below:

```php
class CustomCollection extends \Bonnier\IndexSearch\Content\ContentCollection {
	public function customFilter() {
		$this->service->getHttpRequest()->addPostData('q', $query);
		return $this;
	}
}

class ServiceCustom extends \Bonnier\IndexSearch\ServiceContent {
    /**
     * This event is fired when a collection is returned from the service
     *
     * @return CustomCollection
     */
    public function onCreateCollection() {
        return new CustomCollection($this->service);
    }

    /**
     * This event is fired when a single item is returned from the service
     *
     * @return self
     */
    public function onCreateItem() {
        return new self($this->service->getUsername(), $this->service->getSecret());
    }
    
    public function getCustomItem($id) {
        return $this->api('/custom/item/' . $id);
    }
}
```

Usage:

```php
$custom = new ServiceCustom($username, $secret);
$item = $custom->getCustomCollection(); // This will return a new instance of ServiceCustom class


$results = $custom->getCollection()->customFilter()->execute(); // This will return new instance of ServiceCollection class
```
