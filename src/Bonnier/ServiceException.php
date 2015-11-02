<?php
namespace Bonnier;

use Pecee\Http\Rest\RestException;

class ServiceException extends RestException {

	protected $httpResponse;

	public function __construct($message, $code = 0, HttpResponse $httpResponse = null) {
		parent::__construct($message , $code);

		$this->httpResponse = $httpResponse;
	}

	public function getHttpResponse() {
		return $this->httpResponse;
	}

}