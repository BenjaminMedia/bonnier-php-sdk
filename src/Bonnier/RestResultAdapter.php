<?php

namespace Bonnier;

abstract class RestResultAdapter implements IRestResult {

	/**
	 * @var RESTBase
	 */
	protected $service;

	public function api($url = null, $method = RestBase::METHOD_GET, array $data = null) {
		return $this->service->api($url, $method, $data);
	}

	/**
	 * Execute api call.
	 *
	 * Alias for $this->api();
	 *
	 * @return HttpRequest
	 */
	public function execute() {
		return $this->api();
	}

	public function getService() {
		return $this->service;
	}

}