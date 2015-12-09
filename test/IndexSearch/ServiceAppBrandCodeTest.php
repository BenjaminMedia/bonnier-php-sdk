<?php

class ServiceAppBrandCodeTest extends PHPUnit_Framework_TestCase  {

	const IS_USERNAME = 'Bilindex';
	const IS_SECRET = '3E8AFD2A9544EA01A05F641049E59380';

	public function testCheckFail() {

		$item = new \Bonnier\IndexSearch\ServiceAuth(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);

		$access = false;

		try {
			$newRole = $item->check('phpunit_test');
			$access = $newRole->access;
		}catch(\Bonnier\ServiceException $e) {
		}

		$this->assertFalse($access);
	}

	public function testCheck() {

		$item = new \Bonnier\IndexSearch\ServiceAppBrandCode(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);
		$check = $item->check('test', 'test');

		$this->assertTrue($check);
	}

	public function testList() {

		$item = new \Bonnier\IndexSearch\ServiceAppBrandCode(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);
		$list = $item->getList();

		$this->assertTrue((count($list) > 0));

	}

}
