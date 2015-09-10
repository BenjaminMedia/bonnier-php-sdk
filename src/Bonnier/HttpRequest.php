<?php
namespace Bonnier;

use Doctrine\Instantiator\Exception\InvalidArgumentException;

class HttpRequest {

	protected $url;
	protected $method;
	protected $headers;
	protected $options;
	protected $data;
	protected $timeout;

	public function __construct($url = NULL) {

		if (!function_exists('curl_init')) {
			throw new \Exception('This service requires the CURL PHP extension.');
		}

		$this->url = $url;
		$this->reset();
	}

	public function reset() {
		$this->url = NULL;
		$this->options = array();
		$this->headers = array();
		$this->data = array();
	}

	public function addHeader($header) {
		$this->headers[] = $header;
	}

	public function setHeaders(array $headers) {
		$this->headers = $headers;
	}

	public function getHeaders() {
		return $this->headers;
	}

	public function addOption($option, $value) {
		$this->options[$option] = $value;
	}

	public function setOptions(array $options) {
		$this->options = $options;
	}

	public function getOptions() {
		return $this->options;
	}

	public function addPostData($key, $value) {
		$this->data[$key] = $value;
	}

	public function setPostData(array $data) {
		$this->data = $data;
	}

	public function getPostData() {
		return $this->data;
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

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	public function execute($return) {

		if(is_null($this->url)) {
			throw new InvalidArgumentException('Missing required property: url');
		}

		$handle = curl_init();

		curl_setopt($handle, CURLOPT_URL, $this->url);

		if($return) {
			curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
		}

		if($this->timeout) {
			curl_setopt($handle, CURLOPT_TIMEOUT_MS, $this->timeout);
		}

		// Add headers
		if(is_array($this->headers) && count($this->headers)) {
			curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);
		}

		// Add request data
		if(strtolower($this->method) == 'post' && is_array($this->data)) {
			curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($this->data));
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