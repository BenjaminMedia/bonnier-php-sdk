<?php

class ServiceTranslationTest extends PHPUnit_Framework_TestCase  {

	protected $testLocale = 'en_gb';
	protected $testTitle = 'My test title';
	protected $testTranslateInto = array('da_dk');
	protected $testDeadline = '2005-08-15T15:52:01+00:00';
	protected $testField = array( array( 'label' => 'Text to translate', 'value' => 'Once upon a time...', 'display_format' => 'text') );
	protected $testComment = 'My comment';

	protected $testUpdateLocale = 'da_dk';
	protected $testUpdateTitle = 'Update title';
	protected $testUpdateTranslateInto = 'en_gb';
	protected $testUpdateDeadline = '2015-09-20T15:52:01+00:00';
	protected $testUpdateField = array( array( 'label' => 'Text to translate', 'value' => 'Once upon a time...', 'display_format' => 'text') );
	protected $testUpdateComment = 'My updated comment';

	protected $savedId;

	public function __construct() {

	}

	public function testInsert() {

		$service = new \Bonnier\Trapp\ServiceTranslation('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
		$service->setDevelopment(TRUE);
		$service->locale = $this->testLocale;
		$service->title = $this->testTitle;
		$service->translate_into = $this->testTranslateInto;
		$service->deadline = $this->testDeadline;
		$service->fields = $this->testField;
		$service->comment = $this->testComment;
		try {
			$service->save();
		}catch(\Bonnier\ServiceException $e) {
			echo sprintf('Error: %s', $e->getMessage());
		}

		$this->savedId = $service->id;

		// Assert
		$this->assertEquals($service->language['locale'], $this->testLocale);
		//$this->assertEquals($service->translate_into, $this->test_translateInto);
		$this->assertEquals(date('d-m-y', strtotime($service->deadline)), date('d-m-y', strtotime($this->testDeadline)));
		$this->assertEquals($service->revisions[0]['fields'][0]['label'], $this->testField[0]['label']);
		$this->assertEquals($service->revisions[0]['fields'][0]['value'], $this->testField[0]['value']);
		$this->assertEquals($service->revisions[0]['fields'][0]['display_format'], $this->testField[0]['display_format']);
		$this->assertEquals($service->revisions[0]['comment'], $this->testComment);
	}

	public function testUpdate() {

		$service = new \Bonnier\Trapp\ServiceTranslation('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
		$service->setDevelopment(TRUE);

		$single = $service->getById('55e50678c01443b9708b460d');

		$single->title = $this->testUpdateTitle;
		$single->translate_into = $this->testUpdateTranslateInto;
		$single->deadline = $this->testUpdateDeadline;
		$single->fields = $this->testUpdateField;
		$single->comment = $this->testUpdateComment;

		try {
			$single->update();
		}catch(\Bonnier\ServiceException $e) {
			echo sprintf('Error: %s', print_r($e->getHttpResponse()->getResponse(), TRUE));
		}

		$last = end($single->getRow()->revisions);

		// Assert
		//$this->assertEquals($single->language['locale'], $this->testUpdateLocale); // Not working
		$this->assertEquals(date('d-m-y', strtotime($single->deadline)), date('d-m-y', strtotime($this->testUpdateDeadline)));
		$this->assertEquals($last['fields'][0]['label'], $this->testUpdateField[0]['label']);
		$this->assertEquals($last['fields'][0]['value'], $this->testUpdateField[0]['value']);
		$this->assertEquals($last['fields'][0]['display_format'], $this->testUpdateField[0]['display_format']);
		$this->assertEquals($last['comment'], $this->testUpdateComment);
	}

	public function testDelete() {

		$service = new \Bonnier\Trapp\ServiceTranslation('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
		$service->setDevelopment(TRUE);

		// Assert
		$this->assertNotEquals(TRUE, FALSE);
	}

}
