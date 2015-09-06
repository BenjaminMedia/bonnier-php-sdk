<?php
/***
 * This abstract class contains the logic for REST-Services that follows
 * the structure of the IndexSearch service.
 *
 * Use when the following criterias are met:
 * - Your services uses authentication from IndexSearch
 * - Your services returns rows for collections
 * - Your services throws error (message) and status (
 */
namespace Bonnier\IndexSearch;

use Bonnier\HttpResponse;
use Bonnier\IRestEventListener;
use Bonnier\RestBase;
use Bonnier\ServiceException;

abstract class ServiceRestBase extends RestBase {

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $secret;

	/**
	 * @var HttpResponse
	 */
	protected $httpResponse;

	/**
	 * @var \Bonnier\IRestEventListener
	 */
	protected $serviceEventListener;

	public function __construct($username, $secret) {
		$this->username = $username;
		$this->secret = $secret;
		parent::__construct();
	}

	/**
	 * Parses the API-response and returns either a collection object or single item depending on the results.
	 *
	 * @param HttpResponse $httpResponse
	 * @return mixed
	 * @throws ServiceException
	 */
	protected function onResponseReceived(HttpResponse $httpResponse) {

		if(is_null($this->serviceEventListener)) {
			throw new ServiceException('You must define a ServiceEventListener when using this class');
		}

		$this->httpResponse = $httpResponse;

		$response = json_decode($httpResponse->getResponse(), true);

		// Parse the results
		if(!is_array($response) || $response && isset($response['status'])) {
			$error = (isset($response['error'])) ? $response['error'] : 'API returned invalid response.';
			$status = (isset($response['status'])) ? $response['status'] : 0;

			throw new ServiceException($error, $status, $httpResponse);
		}

		// Check if the result is a collection of items
		if(isset($response['rows'])) {

			$result = $this->serviceEventListener->onCreateCollection();

			// If the item is an instance of IServiceCollection we can set the response
			if($result instanceof IServiceCollection) {
				$result->setResponse($httpResponse, $response);
			}

			$items = array();

			foreach($response['rows'] as $row) {
				$item = $this->serviceEventListener->onCreateItem();
				$item->setRow((object)$row);
				$items[] = $item;
			}

			$result->setRows($items);
			return $result;
		}

		// It wasn't a collection of items, so we just return a single item
		$item = $this->serviceEventListener->onCreateItem();
		$item->setRow((object)$response);
		return $item;
	}

	public function api($url = null, $method = self::METHOD_GET, array $data = array()) {

		$this->httpRequest->setOptions(array(
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false
		));

		$this->httpRequest->setTimeout(10000);

		$data = array_merge($this->httpRequest->getPostData(), $data);

		// Add authentication required by index-search
		$this->httpRequest->setBasicAuth($this->username, $this->secret);

		// Execute the API-call
		return $this->onResponseReceived( parent::api($url, $method, $data) );
	}

	/**
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @return HttpResponse
	 */
	public function getHttpResponse() {
		return $this->httpResponse;
	}

	/**
	 * @return IRestEventListener
	 */
	public function getServiceEventListener() {
		return $this->serviceEventListener;
	}

	/**
	 * @param IRestEventListener $serviceEventListener
	 */
	public function setServiceEventListener(IRestEventListener $serviceEventListener) {
		$this->serviceEventListener = $serviceEventListener;
	}

}