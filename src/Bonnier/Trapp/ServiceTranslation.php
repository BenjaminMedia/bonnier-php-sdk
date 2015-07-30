<?php
namespace Bonnier\Trapp;

use Bonnier\IndexDB\Content\TranslationCollection;
use Bonnier\ServiceException;

class ServiceTranslation extends TrappBase {
	public function __construct($username, $secret) {
		parent::__construct($username, $secret);
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

	public function onCreateResult() {
		return new TranslationCollection($this->username, $this->secret);
	}

	/**
	 * @return TranslationCollection
	 */
	public function get() {
		return new TranslationCollection($this->username, $this->secret);
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