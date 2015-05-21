<?php

require_once '../src/Bonnier/Service.php';
require_once '../src/Bonnier/Service/ServiceException.php';
require_once '../src/Bonnier/Service/ServiceResult.php';
require_once '../src/Bonnier/Service/ServiceItem.php';


$service = new \Bonnier\Service('D97B2EE2D0FFC765501FEF5F76C95C62');

// Get single
$single = $service->getById('FDE455B92EEBC96F72F2447D6AD17C40');

echo '<pre>'.print_r($single, TRUE).'</pre>';

echo '<hr/><h3>Single update</h3>';

$single->source->title = 'LORTE test';
$response = $single->update();

echo '<pre>'.print_r($response, TRUE).'</pre>';

echo '<hr/>';

// Get list with filter
$results = $service->get()->query('hello')->api();

echo '<pre>'.print_r($results, TRUE).'</pre>';