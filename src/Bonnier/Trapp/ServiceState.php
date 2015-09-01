<?php
namespace Bonnier\Trapp;

use Bonnier\RestItem;

class ServiceState extends RestItem {

	const TYPE = 'state';

	public function __construct($username, $secret) {
		parent::__construct($username, $secret, self::TYPE);
	}

	/**
	 * @param $id
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