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
		return 'http://staging-trapp.whitealbum.dk/api/v1/' . $this->type;
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		return $this;
	}

}