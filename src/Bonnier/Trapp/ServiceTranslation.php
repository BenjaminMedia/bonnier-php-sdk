<?php
namespace Bonnier\Trapp;

use Bonnier\RestItem;
use Bonnier\Trapp\Translation\TranslationCollection;
use Bonnier\Trapp\Translation\TranslationItem;

class ServiceTranslation extends RestItem {

	const TYPE = 'translation';

	public function __construct($username, $secret) {
		parent::__construct($username, $secret, self::TYPE);
		$this->postJson = TRUE;
	}

	/**
	 * @param $id
	 * @throws \Bonnier\ServiceException
	 * @return self
	 */
	public function getById($id) {
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

}