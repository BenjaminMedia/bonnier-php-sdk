<?php

class ServiceApplicationTest extends PHPUnit_Framework_TestCase  {

	const IS_USERNAME = 'Bilindex';
	const IS_SECRET = '3E8AFD2A9544EA01A05F641049E59380';

	public function testList() {

		$item = new \Bonnier\IndexSearch\ServiceApplication(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);

		$applications = $item->getCollection()->execute();

		$this->assertTrue((count($applications) > 0));
	}

	public function testSingle() {

		$item = new \Bonnier\IndexSearch\ServiceApplication(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);
		$application = $item->getById(1);

		$this->assertTrue(is_int($application->id));
	}

}
