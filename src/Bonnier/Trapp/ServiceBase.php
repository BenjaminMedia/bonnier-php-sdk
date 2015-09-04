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

	// TODO: Add production/staging enviroment when ready (see above)

	protected function getServiceUrl() {
		/*if($this->development) {
			$this->serviceUrl = 'http://staging-trapp.whitealbum.dk/api/v1/%1$s/';
		} else {
			$this->serviceUrl = 'http://trapp.whitealbum.dk/api/v1/%1$s/';
		}*/

		$this->serviceUrl = 'http://staging-trapp.whitealbum.dk/api/v1/%1$s/';

		return sprintf($this->serviceUrl, $this->type);
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		return $this;
	}

}