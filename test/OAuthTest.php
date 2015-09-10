<?php

class OAuthTest extends PHPUnit_Framework_TestCase  {

	protected $oauth;

	public function __construct() {
		$this->oauth = new \Bonnier\Trapp\ServiceTranslation('Translation', '6277FFAA5D43DEBAF11B62AEB25FB9B5');
	}

	public function testAuthenticate()
	{

		$this->service->locale = $this->test_locale;
		$this->service->title = $this->test_title;
		$this->service->translate_into = $this->test_translateInto;
		$this->service->deadline = $this->test_deadline;
		$this->service->fields = $this->test_field;
		$this->service->comment = $this->test_comment;

		try {
			$this->service->save();
		}catch(\Bonnier\ServiceException $e) {
			die(var_dump($e->getResponse()));
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

}
