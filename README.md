# bonnier-php-sdk
PHP SDK for talking with the Bonnier search index webservice.

Examples
------------

Even though these examples are pretty rough, they should give you a basic understanding on how to use the service class. All classes extends from the \Bonnier\Service class - which contains the basic functionality for talking with the webservice.

Initialize service
-----
```php
$service = new \Bonnier\Service($secret);
```

Get single
-----
```php
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');
```

Update single
-----
```php
$single->source->title = 'Hello world';
$single->update();
```

Save single
-----
```php
$item = array('title' => 'Hello world');

$single = new \Bonnier\Service\ServiceItem($secret);
$single->title = 'Hello world'; // Set single property
$single->setSource($item); // Set entire source
$single->save();
```

Get list, add query and apply filters
-----
```php
$results = $service->get()->query('hello')->addFilter('title', 'myFilter')->addFilter('content', 'secondFilter')->api();
```

Advanced usage
-----

Use DSL to apply even greater filters, by using the dsl method on the \Bonnier\Service\ServiceResult class. Please see the Elasticsearch documentation on how to use DSL:
https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl.html

```php
$filter = array('body' => array('query' => 'match' => array('testField' => 'abc')));
$results = $service->get()->setDsl($filter);
```
