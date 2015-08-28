<?php
namespace Bonnier;

class HttpRequest {

	protected $url;
	protected $method;
	protected $headers;
	protected $options;
	protected $data;
	protected $timeout;

	public function __construct($url) {

		if (!function_exists('curl_init')) {
			throw new \Exception('This service requires the CURL PHP extension.');
		}

		$this->url = $url;

		$this->options = array();
		$this->headers = array();
		$this->data = array();
	}

	public function addHeader($header) {
		$this->headers[] = $header;
	}

	public function addOption($option, $value) {
		$this->options[$option] = $value;
	}

	public function setOptions(array $options) {
		$this->options = $options;
	}

	public function addPostData($key, $value) {
		$this->data[$key] = $value;
	}

	public function setPostData(array $data) {
		$this->data = $data;
	}

	public function post($return = FALSE) {
		$this->options[CURLOPT_POST] = TRUE;
		$this->execute($return);
	}

	public function get($return = FALSE) {
		// Alias for execute
		$this->execute($return);
	}

	public function setTimeout($timeout) {
		$this->timeout = $timeout;
	}

	public function setMethod($method) {
		$this->method = $method;
	}

	public function execute($return) {

		$handle = curl_init($this->url);

		curl_setopt($handle, CURLOPT_URL, $this->url);

		if($return) {
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		}

		if($this->timeout) {
			curl_setopt($handle, CURLOPT_TIMEOUT_MS, $this->timeout);
		}

		// Add headers
		if(count($this->headers)) {
			curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);
		}

		// Add request data
		if(count($this->data)) {
			curl_setopt($handle, CURLOPT_POSTFIELDS, $this->data);
		}

		// Add custom curl options
		if(count($this->options)) {
			foreach($this->options as $option => $value) {
				curl_setopt($handle, $option, $value);
			}
		}

		// Add request method
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, $this->method);

		return new HttpResponse($handle);

	}

}