# bonnier-php-sdk
PHP SDK for talking with the Bonnier search index webservice.

Examples
------------

```php
// Initialize service
$service = new \Bonnier\Service('D97B2EE2D0FFC765501FEF5F76C95C62');
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
$item->setSource($single);
$item->save();
```

```php
// Get list with filter
$results = $service->get()->query('hello')->addFilter('title', 'myFilter')->api();
```
