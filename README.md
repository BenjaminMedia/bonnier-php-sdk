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
$service = new \Bonnier\Service($secret);
```

#### Get single
```php
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');
```

#### Update single
```php
$single->source->title = 'Hello world';
$single->update();
```

#### Save single
Create a new instance of the ServiceItem class. 

Set your custom properties on the source property OR use the setSource method to set your custom array.

```php
$item = array('title' => 'Hello world');

$single = new \Bonnier\Service\ServiceItem($secret);
$single->source->title = 'Hello world';
$single->setSource($item); // Set entire source
$single->save();
```

#### Get list, add query and apply filters
Get the results sets, query everything matching "hello", filter title by "myFilter" and content by "secondFilter". api() makes the final call to the webservice.
```php
$results = $service->get()->query('hello')->addFilter('title', 'myFilter')->addFilter('content', 'secondFilter')->setSort('title')->setOrder('desc')->api();
```

#### Advanced usage

Use DSL to apply even greater filters, by using the "setDsl" method on the \Bonnier\Service\ServiceResult class. Please refer to the Elasticsearch documentation and examples on how to use DSL:
https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html

```php
$filter = array('body' => array('query' => 'match' => array('testField' => 'abc')));
$results = $service->get()->setDsl($filter);
```
