<?php

use Bonnier\Trapp\Translation\TranslationLanguage;
use Faker\Factory;

class ServiceAppBrandCodeTest extends PHPUnit_Framework_TestCase  {

    protected $apiUser, $apiKey;

	public function __construct() {
        $this->apiUser = getenv('TRAPP_API_USER');
        $this->apiKey = getenv('TRAPP_API_KEY');
	}

    public function testService() {
        
        $service = new \Bonnier\IndexSearch\V1\ServiceAppBrandCode($this->apiUser, $this->apiKey);

        $service->setDevelopment(true);

        $appCodes = $service->getAppCodes();
        $brandCodes = $service->getBrandCodes();
        $combinations = $service->getList();

        $this->assertNotEmpty($appCodes);
        $this->assertNotEmpty($brandCodes);
        $this->assertNotEmpty($combinations);

        $this->assertTrue(
            $service->checkCombination($combinations[0]['app_code']['value'], $combinations[0]['brand_code']['value'])
        );
    }



}
