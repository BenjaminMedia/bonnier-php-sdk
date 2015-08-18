<?php
namespace Bonnier\Trapp;
use Bonnier\ServiceItem;

abstract class TrappBase extends ServiceItem {

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
		return 'http://local.trapp.dk/api/v1/' . $this->type;
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
	}

}