<?php

function __autoload($file) {
    include '../src/' . str_replace('\\', DIRECTORY_SEPARATOR, $file) . '.php';
}

$bonnierAdmin = new \Bonnier\Admin\BonnierAdmin('b6d6e6d0b08c7d12d10d15a5884321cdee7d0215f884821d8cbc6f41440ed89c', 'a84cd814e21fe95114513ae13e639e3017bd2a57c494e304177fc7ab279cdba6');

$user = $bonnierAdmin->getUser();

if(!$user) {
    echo $bonnierAdmin->getLoginUrl('http://www.google.com');
}

die();

/*$data = array("name" => "Hagrid", "age" => "36");
$data_string = json_encode($data);

$ch = curl_init('http://local.trapp.dk/api/v1/entity');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string),
        'Authorization: Basic ' . base64_encode('Translation:6277FFAA5D43DEBAF11B62AEB25FB9B5')
));

$result = curl_exec($ch);

die(var_dump($result));*/

$service = new \Bonnier\IndexDB\ServiceContent('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
$service->setDevelopment(TRUE);
/*$service->lang = 'da_dk';
$service->save();*/

//$service = $service->getById('55a8cb09214f48032700421f');
//$service->update_endpoint_uri = 'http://www.google.dk/images/?q=alf';
die(var_dump($service->getById('C40323C9B70D3C26D282BAB4EDCC3B76')));

// Save new item example

/*$service = new \Bonnier\Trapp\ServiceTranslation('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
$result = $service->getById('55a8cb09214f48032700421f');

$result->deadline = 'en';

$update = $result->update();

die(var_dump($update));*/

/*$service = new \Bonnier\Trapp\ServiceTranslation('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
$service->locale = 'easd';
$response = $service->save();

die(var_dump($response));*/

/*$service = new \Bonnier\IndexDB\ServiceContent('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
$role = $service->getById('472411B3EEE17052A861D1C34DF9C646');

/*$service = new \Bonnier\IndexDB\ServiceContent('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
$single = $service->get()->order('test')->api();*/

/*die(var_dump($role));*/

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