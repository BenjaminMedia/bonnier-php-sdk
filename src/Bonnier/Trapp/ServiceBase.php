<?php
namespace Bonnier\Trapp;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;

class ServiceBase extends Client {

	const SERVICE_URL = 'http://trapp.whitealbum.dk/api/v1/';
	const DEV_SERVICE_URL = 'http://staging-trapp.whitealbum.dk/api/v1/';
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_PUT = 'PUT';
	const METHOD_DELETE = 'DELETE';

	private $serviceEndpoint = null;
	protected $user;
	protected $secret;
	protected $development;
	protected $type;
	protected $row = [];
	protected $debug;
	protected $serviceUrl;

	public function __construct($user, $secret, $type, $development = false, $serviceEndpoint = null) {

		$this->user = $user;
		$this->secret = $secret;
		$this->serviceEndpoint = $serviceEndpoint;
		$this->development = $development;
		$this->type = $type;

		$this->setupClient();
	}

	private function setupClient() {
		parent::__construct([
			'base_uri' => $this->getServiceUrl(),
			'auht' => [$this->user, $this->secret],
			'curl' => [
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_FRESH_CONNECT => 1
			],
			'timeout' => 0
		]);
	}

	public function getServiceUrl() {

		if(!$this->serviceEndpoint) {
			if ($this->development) {
				$this->serviceUrl =  self::DEV_SERVICE_URL . (empty($this->type) ? '' : '%1$s/');
			} else {
				$this->serviceUrl =  self::SERVICE_URL . (empty($this->type) ? '' : '%1$s/');
			}
		} else {
			$this->serviceUrl =  $this->serviceEndpoint . (empty($this->type) ? '' : '%1$s/');
		}

		return sprintf($this->serviceUrl, $this->type);
	}

	public function setServiceUrl($url) {
		$this->serviceEndpoint = $url;
		$this->setupClient();
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		$this->setupClient();
		return $this;
	}

	public function api($id, $method = self::METHOD_GET, $data = []) {

		$options = [
			'json' => $data,
		];

		if($this->debug) {
			$options['handler'] = $this->debug;
		}

		$request = $this->request($method, $id, $options);

		return json_decode($request->getBody(), true);
	}

	public function debugRequest($callback) {
		// Grab the client's handler instance.
		$clientHandler = $this->getConfig('handler');
		// Create a middleware that taps into the request
		$tapMiddleware = Middleware::tap($callback);
		// Apply the middleware to the handler
		$this->debug = $tapMiddleware($clientHandler);
	}

	public function getRow() {
		return $this->row;
	}

	public function setRow($row) {
		return $this->row = (array)$row;
	}

	public function toArray() {
		return (array)$this->row;
	}

	public function __set($name, $value) {
		$this->row[$name] = $value;
	}

	public function __get($name) {
		if (isset($this->row[$name])){
			return $this->row[$name];
		}
		return null;
	}

	public function __isset($name)
	{
		return isset($this->row[$name]);
	}

	public function __call($name, $arguments)
	{

	}


}