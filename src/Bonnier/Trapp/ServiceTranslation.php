<?php
namespace Bonnier\Trapp;

use Bonnier\Trapp\Translation\TranslationCollection;

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
		return new TranslationCollection($this->username, $this->secret, $this->type);
	}

	/**
	 * @return TranslationCollection
	 */
	public function getCollection() {
		return new TranslationCollection($this->username, $this->secret, $this->type);
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