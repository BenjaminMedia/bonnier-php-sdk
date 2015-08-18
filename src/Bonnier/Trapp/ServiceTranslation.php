<?php
namespace Bonnier\Trapp;

use Bonnier\Trapp\Translation\TranslationCollection;
use Bonnier\Trapp\Translation\TranslationItem;

class ServiceTranslation extends TrappBase {

	const TYPE = 'translation';

	public function __construct($username, $secret) {
		parent::__construct($username, $secret, self::TYPE);
		$this->postJson = TRUE;
	}

	/**
	 * @param $id
	 *
	 * @return ServiceTranslation
	 * @throws \Bonnier\ServiceException
	 */
	public function getById($id) {
		return $this->api($id);
	}

	protected function onCreateResult() {
		$collection = TranslationCollection($this->username, $this->secret, $this->type);
		$collection->setDevelopment(TRUE);
		return $collection;
	}

	protected function onCreateItem() {
		$item = new TranslationItem($this->username, $this->secret, $this->type);
		$item->setDevelopment($this->development);
		return $item;
	}

	/**
	 * @return TranslationCollection
	 */
	public function getCollection() {
		$collection = TranslationCollection($this->username, $this->secret, $this->type);
		$collection->setDevelopment($this->development);
		return $collection;
	}

	/**
	 * @return ServiceTranslation
	 * @param $id string
	 * @throws \Bonnier\ServiceException
	 */
	public function delete($id) {
		return $this->api($id, self::METHOD_DELETE);
	}

}