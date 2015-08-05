<?php
namespace Bonnier\Trapp;

use Bonnier\Trapp\Translation\TranslationCollection;

class ServiceLanguage extends TrappBase {

	const TYPE = 'language';

	public function __construct($username, $secret) {
		parent::__construct($username, $secret, self::TYPE);
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

	/**
	 * @return \Bonnier\ServiceResult
	 */
	public function getCollection() {
		return $this->api();
	}

	/**
	 * @return \Bonnier\ServiceItem
	 * @param $id string
	 * @throws \Bonnier\ServiceException
	 */
	public function delete($id) {
		return $this->api($id, self::METHOD_DELETE);
	}
}