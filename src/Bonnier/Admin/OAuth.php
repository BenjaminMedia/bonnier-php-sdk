<?php
namespace Bonnier\Admin;

use Bonnier\RESTBase;

// TODO: Rename this class

class OAuth {

	public function __construct() {
		parent::__construct();
	}

	protected function getServiceUrl() {
		return 'https://bonnier-admin.herokuapp.com/oauth/token';
	}

	public function getToken($id, $secret) {

		$data = array(
			'grant_type' => 'client_credentials',
			'client_id' => $id,
			'client_secret' => $secret
		);

		return $this->api(NULL, self::METHOD_POST, $data);

	}

	/*protected function getServiceUrl() {
		return 'https://bonnier-admin.herokuapp.com/api/users/current.json'; // TODO: Change the autogenerated stub
	}*/

	public function authenticate() {

	}

	public function current() {
		return $this->api('current.json');
	}


}