<?php
namespace Bonnier\Admin;

use Bonnier\RestBase;

// TODO: Rename this class

class OAuth {

	public function __construct() {
		parent::__construct();
	}

	public function getServiceUrl() {
		return 'https://bonnier-admin.herokuapp.com/oauth/token';
	}

	public function getToken($id, $secret) {

		$data = array(
			'grant_type' => 'client_credentials',
			'client_id' => $id,
			'client_secret' => $secret
		);

		return $this->api(null, self::METHOD_POST, $data);

	}

	public function authenticate() {

	}

	public function current() {
		return $this->api('current.json');
	}


}