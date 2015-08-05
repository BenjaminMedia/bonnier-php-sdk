<?php
namespace Bonnier;
class ServiceException extends \Exception {

	protected $response;

	public function __construct($message, $code = 0, $originalResponse = NULL) {
		parent::__construct($message , $code);

		$this->response = $originalResponse;
	}

	public function setResponse($response) {
		$this->response = $response;
	}

	public function getResponse() {
		return $this->response;
	}

}