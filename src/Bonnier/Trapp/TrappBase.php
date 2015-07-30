<?php
namespace Bonnier\Trapp;
use Bonnier\RESTBase;

abstract class TrappBase extends RESTBase {

	protected $development;
	protected $type;

	public function __construct($username, $secret, $type) {
		parent::__construct($username, $secret);
		$this->type = $type;
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
		return 'http://local.trapp.dk/api/v1/entity';
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
	}

}