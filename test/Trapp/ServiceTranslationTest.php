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

        $translateIntoLocale = 'da_dk';
        //$translateInto = new \Bonnier\Trapp\Translation\TranslationLanguage($translateIntoString);

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
        $service->addTranslatation($translateIntoLocale);
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
        $this->assertTrue($return->hasTranslation($translateIntoLocale));
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

        $beforeUpdate = $service->getById($id);
        $unsavedUpdate = $service->getById($id);

        $title = $this->faker->text(80);

        $translateIntoLocale = 'sv_se';

        $deadline = new DateTime('now');
        $comment = $this->faker->text();
        $testField = array(
            'label' => $this->faker->sentence(3),
            'value' => $this->faker->paragraph,
            'display_format' => 'text'
        );
        $field =  \Bonnier\Trapp\Translation\TranslationField::fromArray($testField);

        $unsavedUpdate->setAppCode($this->appCode);
        $unsavedUpdate->setBrandCode($this->brandCode);
        $unsavedUpdate->setTitle($title);
        $unsavedUpdate->addTranslatation($translateIntoLocale);
        $unsavedUpdate->setDeadline($deadline);
        $unsavedUpdate->setComment($comment);
        $unsavedUpdate->addField($field);

        $savedUpdate = null;
		try {
            $savedUpdate = $unsavedUpdate->update();
		}catch(Exception $e) {

			echo sprintf('Error: %s', print_r($e->getHttpResponse()->getResponse(), true));
            die();
		}

        // Get id from returned translation
        $id = $savedUpdate->getId();
        // Check that the id is actually set
        $this->assertNotNull($id);

        // Ensure the correct properties remains on set object.
        $this->assertEquals($this->appCode, $savedUpdate->getAppCode());
        $this->assertEquals($this->brandCode, $savedUpdate->getBrandCode());
        $this->assertEquals($title, $savedUpdate->getTitle());

        $this->assertTrue($savedUpdate->hasTranslation($translateIntoLocale));
        $this->assertEquals($deadline, $savedUpdate->getDeadline());
        $this->assertEquals($comment, $savedUpdate->getComment());

        // Ensure the correct properties was set on object.
        $this->assertNotEquals($beforeUpdate->getAppCode(), $savedUpdate->getAppCode());
        $this->assertNotEquals($beforeUpdate->getBrandCode(), $savedUpdate->getBrandCode());
        $this->assertNotEquals($beforeUpdate->getLocale(), $savedUpdate->getLocale());
        $this->assertNotEquals($beforeUpdate->getTitle(), $savedUpdate->getTitle());
        $this->assertNotEquals($beforeUpdate->getTranslateInto()[0]['locale'], $savedUpdate->getTranslateInto()[1]['locale']);
        $this->assertNotEquals($beforeUpdate->getDeadline(), $savedUpdate->getDeadline());
        $this->assertNotEquals($beforeUpdate->getComment(), $savedUpdate->getComment());
        // Check that the field was correctly returned with expected values
        $this->assertNotEquals($beforeUpdate->getFields(), $savedUpdate->getFields());

	}

}
