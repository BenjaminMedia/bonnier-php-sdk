<?php
namespace Bonnier\Trapp;

use Bonnier\IndexSearch\ServiceRestBase;

class ServiceBase extends ServiceRestBase {

	protected $development;
	protected $type;

	public function __construct($username, $secret, $type) {
		parent::__construct($username, $secret);
		$this->type = $type;
	}

	public function getServiceUrl() {
		if(!$this->serviceUrl) {
			if ($this->development) {
				$this->serviceUrl = 'http://staging-trapp.whitealbum.dk/api/v1/';
			} else {
				$this->serviceUrl = 'http://trapp.whitealbum.dk/api/v1/';
			}
		}

		$this->serviceUrl .= empty($this->type) ? '' : '%1$s/';

		return sprintf($this->serviceUrl, $this->type);
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		return $this;
	}

}