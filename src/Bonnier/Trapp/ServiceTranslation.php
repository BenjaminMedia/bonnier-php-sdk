<?php
namespace Bonnier\Trapp;

use Bonnier\RestItem;
use Bonnier\ServiceException;
use Bonnier\Trapp\Translation\TranslationCollection;

class ServiceTranslation extends RestItem {

	const TYPE = 'translation';

	/**
	 * This is required in order to get autocompletion to work for this element.
	 * @var ServiceBase
	 */
	protected $service;

	public function __construct($username, $secret) {
		parent::__construct(new ServiceBase($username, $secret, self::TYPE));
		$this->service->setServiceEventListener($this);
		$this->service->getHttpRequest()->setPostJson(true);
	}

	/**
	 * @param $id
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public function getById($id) {
		if(is_null($id)) {
			throw new ServiceException('Invalid argument for parameter $id');
		}

		return $this->api($id);
	}

	public function onCreateCollection() {
		return new TranslationCollection($this->service);
	}

	public function onCreateItem() {
		return new self($this->service->getUsername(), $this->service->getSecret());
	}

	/**
	 * Get queryable translation collection.
	 *
	 * @return TranslationCollection
	 */
	public function getCollection() {
		return $this->onCreateCollection();
	}

	public function setDevelopment($bool) {
		$this->service->setDevelopment($bool);
		return $this;
	}

}