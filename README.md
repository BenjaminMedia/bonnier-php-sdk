# bonnier-php-sdk
PHP SDK for talking with the Bonnier search index webservice.

Example

$service = new \Bonnier\Service('D97B2EE2D0FFC765501FEF5F76C95C62');

// Get single
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

// Update single
$single->source->title = 'LORTE test';
$response = $single->update();

// Get list with filter
$results = $service->get()->query('hello')->addFilter('title', 'myFilter')->api();
