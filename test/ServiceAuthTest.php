<?php

class ServiceAuthTest extends PHPUnit_Framework_TestCase  {

	const IS_USERNAME = 'Bilindex';
	const IS_SECRET = '3E8AFD2A9544EA01A05F641049E59380';

	public function testNoAccess() {

		$item = new \Bonnier\IndexSearch\ServiceAuth(self::IS_USERNAME, self::IS_SECRET);

		$item->setDevelopment(true);
		$newRole = $item->check('test');

		$this->assertFalse($newRole->access);
	}

	public function testHasAccess() {

		$item = new \Bonnier\IndexSearch\ServiceAuth(self::IS_USERNAME, self::IS_SECRET);

		$item->setDevelopment(true);
		$newRole = $item->check('indexsearch_content_index');

		$this->assertTrue($newRole->access);
	}

}
