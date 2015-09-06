<?php
namespace Bonnier;

class HttpResponse {

	protected $handle;
	protected $response;

	public function __construct($handle) {
		$this->handle = $handle;
		$this->response = curl_exec($this->handle);
	}

	public function getInfo() {
		return curl_getinfo($this->handle);
	}

	public function getResponse() {
		return $this->response;
	}

	public function getUrl() {
		return curl_getinfo($this->handle, CURLINFO_EFFECTIVE_URL);
	}

}