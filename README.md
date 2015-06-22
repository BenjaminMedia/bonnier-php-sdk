# bonnier-php-sdk
PHP SDK for communicating with the Bonnier search db webservice.

## Version
0.5

## Notes
**This version is outdated and no longer works with the BonnierSearchDB. Please wait for an updated version of the SDK**

## Examples
------------

Even though these examples are pretty rough, they should give you a basic understanding on how to use the service class. All classes extends from the \Bonnier\Service class - which contains the basic functionality for talking with the webservice.

#### Initialize service
```php
$service = new \Bonnier\ServiceContent($secret);
```

#### Get single
```php
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');
```

#### Update single
```php
$single->item->title = 'Hello world';
$single->item->description = 'My new description';
$response = $single->update(); // Returns new object with updated response from service
```

#### Save single
Create a new instance of the ServiceItem class. 

Set your custom properties on the source property OR use the setSource method to set your custom array.

```php
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

$response = $service->save($item); // Returns new object with updated response from service
```

#### Get list, add query and apply filters
Get the results sets, query everything matching "hello", filter title by "myFilter" and content by "secondFilter". api() makes the final call to the webservice.
```php
$results = $service->get() // Get the queryable ServiceResult object
->query('hello') // Get everything that matches "hello"
->filter('title', 'myFilter') // Filter "title" by "myFilter"
->filter('content', 'secondFilter') // Add as many filters as you like
->sort('title') // Sort by title
->order('asc') // Order results by ASC
->api(); // Call the service and get the results
```

#### Advanced usage

Use DSL to apply even greater filters, by using the "setDsl" method on the \Bonnier\Service\ServiceResult class. Please refer to the Elasticsearch documentation and examples on how to use DSL:
https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html

```php
$filter = array('body' => array('query' => 'match' => array('testField' => 'abc')));
$results = $service->get()->dsl($filter);
```
