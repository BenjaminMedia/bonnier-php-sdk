<?php
namespace Bonnier\Trapp;

use Pecee\Http\Rest\RestItem;

class ServiceState extends RestItem {

	const TYPE = 'state';

	public function __construct($username, $secret) {
		parent::__construct(new ServiceBase($username, $secret, self::TYPE));
		$this->service->setServiceEventListener($this);
	}

	/**
	 * @return self
	 */
	public function onCreateItem(){
		$self = new self($this->service->getUsername(), $this->service->getSecret());
		$self->setService($this->service);
		return $self;
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

	public function setDevelopment($bool) {
		$this->service->setDevelopment($bool);
		return $this;
	}

	/**
	 * @return ServiceBase
	 */
	public function getService() {
		return parent::getService();
	}
}