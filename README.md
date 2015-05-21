# bonnier-php-sdk
PHP SDK for talking with the Bonnier search index webservice.

Examples
------------

Even though these examples are pretty rough, they should give you a basic understanding on how to use the service class. All classes extends from the \Bonnier\Service class - which contains the basic functionality for talking with the Bonnier webservice.

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
$single = array('title' => 'Hello world');
$item = new \Bonnier\Service\ServiceItem($secret);
$item->title = 'Hello world'; // Set single properties, one by one
$item->setSource($single); // Set entire source
$item->save();
```

```php
// Get list with filter
$results = $service->get()->query('hello')->addFilter('title', 'myFilter')->api();
```
