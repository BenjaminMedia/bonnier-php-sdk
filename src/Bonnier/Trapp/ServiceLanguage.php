<?php
namespace Bonnier\Trapp;

use Bonnier\RestItem;

class ServiceLanguage extends RestItem {

	const TYPE = 'language';

	public function __construct($username, $secret) {
		parent::__construct($username, $secret, self::TYPE);
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
}