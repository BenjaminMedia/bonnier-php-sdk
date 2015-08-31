<?php
namespace Bonnier\IndexSearch;

use Bonnier\IndexSearch\REST\RESTBase;
use Bonnier\IndexSearch\REST\RESTCollection;
use Bonnier\IndexSearch\REST\RESTItem;

abstract class ServiceBase extends RESTBase {

	protected $development;
	protected $type;

	public function __construct($username, $secret, $type = '') {
		parent::__construct($username, $secret);
		$this->type = $type;
	}

	// Events
	protected function onCreateCollection() {
		$result = new RESTCollection($this->username, $this->secret, $this->type);
		$result->setDevelopment($this->development);
		return $result;
	}

	protected function onCreateItem() {
		$item = new RESTItem($this->username, $this->secret, $this->type);
		$item->setDevelopment($this->development);
		return $item;
	}

	protected function getServiceUrl() {
		if($this->development) {
			$this->serviceUrl = 'https://staging-indexdb.whitealbum.dk/api/v1/%1$s/';
		} else {
			$this->serviceUrl = 'https://indexdb.whitealbum.dk/api/v1/%1$s/';
		}

		return sprintf($this->serviceUrl, $this->type);
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		return $this;
	}

}