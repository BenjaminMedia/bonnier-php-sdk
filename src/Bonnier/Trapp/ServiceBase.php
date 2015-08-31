<?php
namespace Bonnier\Trapp;

use Bonnier\IndexSearch\REST\RESTBase;

abstract class ServiceBase extends RESTBase {

	protected $development;
	protected $type;

	public function __construct($username, $secret, $type) {
		parent::__construct($username, $secret);
		$this->type = $type;
	}

	// Events
	protected function onCreateCollection() {

		// TODO: WIP NOT IMPLEMENTED YET

		$result = new RESTCollection($this->username, $this->secret, $this->type);
		$result->setDevelopment($this->development);
		return $result;
	}

	protected function onCreateItem() {

		// TODO: WIP NOT IMPLEMENTED YET

		$item = new RESTItem($this->username, $this->secret, $this->type);
		$item->setDevelopment($this->development);
		return $item;
	}

	/*protected function getServiceUrl() {
		if($this->development) {
			$this->serviceUrl = 'https://local.trapp/api/v1/';
		} else {
			$this->serviceUrl = 'https://trapp.whitealbum.dk/api/v1/';
		}

		return sprintf($this->serviceUrl, $this->type);
	}*/

	// TODO: Add production/staging enviroment when ready (see above)

	protected function getServiceUrl() {
		return 'http://staging-trapp.whitealbum.dk/api/v1/' . $this->type;
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		return $this;
	}

}