<?php
namespace Bonnier\Trapp;

use Bonnier\RestItem;

class ServiceLanguage extends RestItem {

	const TYPE = 'language';

	/**
	 * This is required in order to get autocompletion to work for this element.
	 * @var ServiceBase
	 */
	protected $service;

	public function __construct($username, $secret) {
		parent::__construct(new ServiceBase($username, $secret, self::TYPE));
		$this->service->setServiceEventListener($this);
	}

	/**
	 * @return self
	 */
	public function onCreateItem() {
		return new self($this->service->getUsername(), $this->service->getSecret());
	}

	/**
	 * @param $id
	 *
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public function getById($id) {
		return $this->api($id);
	}

	/**
	 * @return \Bonnier\RestCollection
	 */
	public function getCollection() {
		return $this->api();
	}

	public function setDevelopment($bool) {
		$this->service->setDevelopment($bool);
		return $this;
	}
}