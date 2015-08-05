<?php

class ServiceTranslationTest extends PHPUnit_Framework_TestCase  {

	protected $service;

	protected $test_locale = 'en_gb';
	protected $test_translateInto = array('da_dk');
	protected $test_deadline = '2005-08-15T15:52:01+00:00';
	protected $test_field = array( array( 'label' => 'Text to translate', 'value' => 'Once upon a time...', 'display_format' => 'text') );
	protected $test_comment = 'My comment';

	public function __construct() {
		$this->service = new \Bonnier\Trapp\ServiceTranslation('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
	}

	public function testInsert()
	{

		$this->service->locale = $this->test_locale;
		$this->service->translate_into = $this->test_translateInto;
		$this->service->deadline = $this->test_deadline;
		$this->service->fields = $this->test_field;
		$this->service->comment = $this->test_comment;

		try {
			$this->service->save();
		}catch(\Bonnier\ServiceException $e) {
			echo sprintf('Error: %s', $e->getResponse());
		}

		// TODO: Parse response from api


		// Assert

		$this->assertEquals($this->service->locale, $this->test_locale);
		$this->assertEquals($this->service->translate_into, $this->test_translateInto);
		$this->assertEquals($this->service->deadline, $this->test_deadline);
		$this->assertEquals($this->service->fields, json_encode($this->test_field));
		$this->assertEquals($this->service->test_comment, $this->comment);
	}

	public function testUpdate()
	{

		$service = new \Bonnier\Trapp\ServiceTranslation('username', '');




		// Assert
		$this->assertNotEquals(TRUE, FALSE);
	}

	public function testDelete()
	{

		$service = new \Bonnier\Trapp\ServiceTranslation('username', '');




		// Assert
		$this->assertNotEquals(TRUE, FALSE);
	}

}
