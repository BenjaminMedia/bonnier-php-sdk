<?php

require_once '../src/Bonnier/Service/ServiceBase.php';
require_once '../src/Bonnier/Service/ServiceItem.php';
require_once '../src/Bonnier/Service/ServiceResult.php';
require_once '../src/Bonnier/Service/ServiceContentResult.php';
require_once '../src/Bonnier/ServiceContent.php';
require_once '../src/Bonnier/ServiceAuth.php';
require_once '../src/Bonnier/ServiceApplication.php';
require_once '../src/Bonnier/Service/ServiceException.php';



// Save new item example

$service = new \Bonnier\ServiceAuth('A25B21A1127D904E555B9D73A048D703');
$role = $service->check('translate_create');

//$service = new \Bonnier\ServiceApplications('A25B21A1127D904E555B9D73A048D703');

//$service = new \Bonnier\ServiceContent('AAD902EBA6CA5F7C43E742DDF39AB81E');
//$role = $service->get()->api();

//$single = $service->getById('60B80DA10CF40E5E2F60E812B1FD3A77');
/*$single->title = 'DIN MOR';
$single->locale = 'da-dk';
$single->image = 'http://bonnier.imgix.net/cdn-connect/0c91532fb20249a69eae49f67aa8318c.jpg';
$single->description = 'test';
$single->active = TRUE;
$single->created_at = '06-04-1990 10:00';
$single->updated_at = '06-04-1990 10:00';

$single->content_type = 'article';
$single->content_url = 'http://www.google.dk/test';

$test = $single->save();

die('test'.var_dump($test));*/

// $applications = $service->get();

//$single = $service->getById('B87798F6672C8D7EB284EADEC8AAF65C');

/*$single->title = 'Hej med dig';
$single->content_url = 'http://www.bonnierpublications.com/test';
$single->content_type = 'article';
$single = $single->update();*/

//die(var_dump($single));


/*$item = new \Bonnier\Service\ServiceItem('D97B2EE2D0FFC765501FEF5F76C95C62', 'content');
$item->title = 'Hello world'; // Similar to doing $item->title = 'Hello world!';
$item->description = 'My description'; // Similar to doing $item->description = 'My description!';
$item->app_content_id = '23';
$item->source_image = 'http://www.google.dk/test.png';
$item->content_type = '23';

$item->save();*/

/*$item = new stdClass();
$item->title = 'Min titel';
$item->app_content_id = '123123';
$item->description = 'My description';
$item->source_image = 'http://www.revert.dk/logo.png';
$item->content_type = 'test';
$item->created_at = date('d-m-Y', time());
$item->updated_at = date('d-m-Y', time());
$item->path = '/';
$item->active = TRUE;*/

//$service->save($item);

//$response = $service->save($item);

//die(var_dump($single));

// Get single example
/*$single = $service->getById('6159EDE362030FF937927C9A16A4E9D5');
$single->title = 'Ny pisse god titel 9';
$single->description = 'new new description';
$single->update();


die(var_dump($single));*/


// Get list with filter
/*$results = $service->get()->query('titel')->filter('app_id', 10)->limit(5)->skip(0)->api();
foreach($results->getRows() as $result) {
    echo $result->id . '<br/>';
}

die(var_dump($results));*/


/*
// Get single


echo '<pre>'.print_r($single, TRUE).'</pre>';

echo '<hr/><h3>Single update</h3>';

$single->source->title = 'LORTE test';
$response = $single->update();

echo '<pre>'.print_r($response, TRUE).'</pre>';

echo '<hr/>';

// Get list with filter
$results = $service->get()->query('hello')->api();

echo '<pre>'.print_r($results, TRUE).'</pre>';*/