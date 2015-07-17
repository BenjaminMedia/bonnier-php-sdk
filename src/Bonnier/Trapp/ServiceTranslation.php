<?php
namespace Bonnier\Trapp;

class ServiceTranslation extends TrappBase {
	const TYPE = 'entity';

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

	/**
	 * @return TranslationResult
	 */
	public function get() {
		return $this;
	}

	public function update() {
		return $this->api($this->_id, self::METHOD_PUT, (array)$this->row);
	}
}