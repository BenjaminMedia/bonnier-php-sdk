# bonnier-php-sdk
PHP SDK for talking with the Bonnier search index webservice.

Examples
------------

Even though these examples are pretty rough, they should give you a basic understanding on how to use the service class. All classes extends from the \Bonnier\Service class - which contains the basic functionality for talking with the webservice.

```php
// Initialize service
$service = new \Bonnier\Service($secret);
```

```php
// Get single
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');
```

```php
// Update single
$single->source->title = 'Hello world';
$single->update();
```

```php
// Save single
$item = array('title' => 'Hello world');

$single = new \Bonnier\Service\ServiceItem($secret);
$single->title = 'Hello world'; // Set single property
$single->setSource($item); // Set entire source
$single->save();
```

```php
// Get list with filter
$results = $service->get()->query('hello')->addFilter('title', 'myFilter')->api();
```
