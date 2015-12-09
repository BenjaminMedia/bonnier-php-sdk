<?php

use Faker\Factory;

class ServiceTranslationTest extends PHPUnit_Framework_TestCase  {

    protected $faker = null;

	public function __construct() {
        $this->faker = Factory::create();
        $this->apiUser = getenv('TRAPP_API_USER');
        $this->apiKey = getenv('TRAPP_API_KEY');
        $this->serviceUrl = getenv('TRAPP_SERVICE_URL');
        $this->appCode = getenv('APP_CODE');
        $this->brandCode = getenv('BRAND_CODE');
	}

	public function testInsert() {
		$service = new \Bonnier\Trapp\ServiceTranslation($this->apiUser, $this->apiKey);
        $service->getService()->setServiceUrl($this->serviceUrl);
        $service->setDevelopment(true);

        $locale = 'en_gb';
        $title = $this->faker->text(80);

        $translateIntoString = 'da_dk';
        $translateInto = new \Bonnier\Trapp\Translation\TranslationLanguage($translateIntoString);

        $deadline = new DateTime('tomorrow');
        $comment = $this->faker->text();
        $testField = array(
            'label' => $this->faker->sentence(3),
            'value' => $this->faker->paragraph,
            'display_format' => 'text'
        );
        $field =  \Bonnier\Trapp\Translation\TranslationField::fromArray($testField);

        // Set attributes
        $service->setAppCode($this->appCode);
        $service->setBrandCode($this->brandCode);
		$service->setLocale($locale);
        $service->setTitle($title);
        $service->addTranslateInto($translateInto);
        $service->setDeadline($deadline);
        $service->setComment($comment);
        $service->addField($field);

        $return = null;
        try {
			$return = $service->save();
		}catch(\Bonnier\ServiceException $e) {
			echo sprintf('Error: %s', $e->getMessage());
            die();
		}

        // Get id from returned translation
        $id = $return->getId();
        // Check that the id is actually set
        $this->assertNotNull($id);
        $this->assertTrue($return->isOriginal());

		// Ensure the correct properties remains on set object.
        $this->assertEquals($this->appCode, $return->getAppCode());
        $this->assertEquals($this->brandCode, $return->getBrandCode());
        $this->assertEquals($locale, $return->getLocale());
        $this->assertEquals($title, $return->getTitle());
        $this->assertEquals($translateInto->getLocale(), $return->getTranslateInto()[0]->getLocale());
		$this->assertEquals($deadline, $return->getDeadline());
        $this->assertEquals($comment, $return->getComment());

        // Fetch the returned field
        $returnField = $return->getFields()[0];

        // Check that the field was correctly returned with expected values
		$this->assertEquals($testField['label'], $returnField->getLabel());
        $this->assertEquals($testField['value'], $returnField->getValue());
        $this->assertEquals($testField['display_format'], $returnField->getDisplayFormat());

        return $id;
	}

    /**
     * @depends testInsert
     * @param $id
     * @throws \Bonnier\ServiceException
     */
	public function testUpdate($id) {
        $service = new \Bonnier\Trapp\ServiceTranslation($this->apiUser, $this->apiKey);
        $service->getService()->setServiceUrl($this->serviceUrl);
        $service->setDevelopment(true);

        $original = $service->getById($id);
        $updated = $service->getById($id);

        $locale = 'da_dk';
        $title = $this->faker->text(80);

        $translateIntoString = 'sv_se';
        $translateInto = new \Bonnier\Trapp\Translation\TranslationLanguage($translateIntoString);

        $deadline = new DateTime('now');
        $comment = $this->faker->text();
        $testField = array(
            'label' => $this->faker->sentence(3),
            'value' => $this->faker->paragraph,
            'display_format' => 'text'
        );
        $field =  \Bonnier\Trapp\Translation\TranslationField::fromArray($testField);

        $updated->setAppCode($this->appCode);
        $updated->setBrandCode($this->brandCode);
        $updated->setLocale($locale);
        $updated->setTitle($title);
        $updated->addTranslateInto($translateInto);
        $updated->setDeadline($deadline);
        $updated->setComment($comment);
        $updated->addField($field);

        var_dump($translateInto);
        die();

        $return = null;
		try {
            $return = $updated->update();
		}catch(Exception $e) {
			echo sprintf('Error: %s', print_r($e->getHttpResponse()->getResponse(), true));
		}

        // Get id from returned translation
        $id = $return->getId();
        // Check that the id is actually set
        $this->assertNotNull($id);

        // Ensure the correct properties remains on set object.
        $this->assertEquals($this->appCode, $return->getAppCode());
        $this->assertEquals($this->brandCode, $return->getBrandCode());
        $this->assertEquals($locale, $return->getLocale());
        $this->assertEquals($title, $return->getTitle());
        $this->assertEquals($translateInto, $return->getTranslateInto()[1]['locale']);
        $this->assertEquals($deadline, $return->getDeadline());
        $this->assertEquals($comment, $return->getComment());
        // Ensure the correct properties was set on object.
        $this->assertNotEquals($original->getAppCode(), $return->getAppCode());
        $this->assertNotEquals($original->getBrandCode(), $return->getBrandCode());
        $this->assertNotEquals($original->getLocale(), $return->getLocale());
        $this->assertNotEquals($original->getTitle(), $return->getTitle());
        $this->assertNotEquals($original->getTranslateInto()[0]['locale'], $return->getTranslateInto()[1]['locale']);
        $this->assertNotEquals($original->getDeadline(), $return->getDeadline());
        $this->assertNotEquals($original->getComment(), $return->getComment());
        // Check that the field was correctly returned with expected values
        $this->assertNotEquals($original->getFields(), $return->getFields());

	}

}
