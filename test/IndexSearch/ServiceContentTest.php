<?php

class ServiceContentTest extends PHPUnit_Framework_TestCase  {

	const IS_USERNAME = 'Bilindex';
	const IS_SECRET = '3E8AFD2A9544EA01A05F641049E59380';

	protected $title = 'Min titel';
	protected $description = 'My description';
	protected $appCode = 'bilindex';
	protected $brandCode = 'bil';
	protected $active = true;
	protected $contentUrl = 'http://www.revert.dk/12213-article.html';
	protected $contentType = 'article';
	protected $locale = 'da_dk';
	protected $image = 'http://buymelaughs.com/wp-content/uploads/2014/01/Funny-Babies-Pictures-2.jpg';
	protected $createdAt;
	protected $updatedAt;

	public function __construct() {
		$this->createdAt = date(DATE_W3C);
		$this->updatedAt = date(DATE_W3C);
	}

	protected function createItem() {
		$item = new \Bonnier\IndexSearch\ServiceContent(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);

		$item->title = $this->title;
		$item->description = $this->description;
		$item->image = $this->image;
		$item->content_type = $this->contentType;
		$item->created_at = $this->createdAt;
		$item->updated_at = $this->updatedAt;
		$item->locale = $this->locale;
		$item->content_url = $this->contentUrl;
		$item->active = $this->active;
		$item->app_code = $this->appCode;
		$item->brand_code = $this->brandCode;
		$item->save();

		return $item;
	}

	public function testInsert() {

		$item = $this->createItem();

		$this->assertNotEmpty($item->id);
		$this->assertEquals($this->title, $item->title);
		$this->assertEquals($this->description, $item->description);
		$this->assertTrue($item->active);
		$this->assertEquals($this->contentUrl, $item->content_url);

		$this->assertNotEmpty($item->images);
		$this->assertNotEmpty($item->created_at);
		$this->assertNotEmpty($item->updated_at);

		$this->createdId = $item->id;

		// createdid property does not seem to work in other methods
	}

	public function testUpdate() {
		$item = new \Bonnier\IndexSearch\ServiceContent(self::IS_USERNAME, self::IS_SECRET);
		$item->setDevelopment(true);
		$item = $item->getById('9ADA1E030D77EA1ED8C2039358FCD667');

		$item->title = $this->title;
		$item->description = $this->description;
		$item->image = $this->image;
		$item->content_type = $this->contentType;
		$item->created_at = $this->createdAt;
		$item->updated_at = $this->updatedAt;
		$item->locale = $this->locale;
		$item->content_url = $this->contentUrl;
		$item->active = $this->active;
		$item->app_code = $this->appCode;
		$item->brand_code = $this->brandCode;

		try {
			$item->update();
		} catch (\Bonnier\ServiceException $e) {
			die(var_dump($e->getHttpResponse()->getInfo()));
		}

		$this->assertNotEmpty($item->id);
		$this->assertEquals($this->title, $item->title);
		$this->assertEquals($this->description, $item->description);
		$this->assertTrue($item->active);
		$this->assertEquals($this->contentUrl, $item->content_url);
		$this->assertNotEmpty($item->images);
		$this->assertNotEmpty($item->created_at);
		$this->assertNotEmpty($item->updated_at);
	}

	public function testList() {
		$service = new \Bonnier\IndexSearch\ServiceContent(self::IS_USERNAME, self::IS_SECRET);
		$service->setDevelopment(true);

		$results = $service->getCollection() // Get the queryable ServiceCollection object
		                   ->contentType('test') // Filter "title" by "myFilter"
		                   ->sort('title') // Sort by title
		                   ->order('asc') // Order results by ASC
		                   ->execute();

		$this->assertTrue((count($results->getRows()) > 0));
	}

	public function testDelete() {
		$item = $this->createItem();
		$this->assertNotEmpty($item->delete());
	}

}
