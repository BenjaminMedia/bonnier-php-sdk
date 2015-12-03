<?php

class ServiceContentType extends PHPUnit_Framework_TestCase  {

	const IS_USERNAME = 'Bilindex';
	const IS_SECRET = '3E8AFD2A9544EA01A05F641049E59380';

	public function testList() {

		$item = new \Bonnier\IndexSearch\ServiceContentType(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);

		$contentType = $item->getById('test');

		$this->assertTrue(is_int($contentType->id));
	}

	public function testSingle() {

		$item = new \Bonnier\IndexSearch\ServiceContentType(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);
		$types = $item->getCollection()->execute();

		$this->assertTrue((count($types) > 0));
	}

}
