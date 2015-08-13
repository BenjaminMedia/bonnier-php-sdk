<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceItem;

abstract class IndexDBBase extends ServiceItem {

	protected $development;
	protected $type;

	public function __construct($username, $secret, $type) {
		parent::__construct($username, $secret);
		$this->type = $type;
	}

	protected function onCreateItem() {
		$self = get_called_class();
		return new $self($this->username, $this->secret, $this->type);
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