<?php
namespace Bonnier\Trapp;

use Bonnier\RESTBase;

abstract class TrappBase extends RESTBase {

	// TODO: Add production/staging enviroment when ready

	public function getUrl() {
		return 'http://local.trapp.dk/api/v1/%1$s/';
	}

}