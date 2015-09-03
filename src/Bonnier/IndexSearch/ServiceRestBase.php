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
use Bonnier\RestBase;
use Bonnier\RestCollection;
use Bonnier\RestItem;
use Bonnier\ServiceException;

abstract class ServiceRestBase extends RestBase {

	protected $username;
	protected $secret;

	public function __construct($username, $secret) {
		$this->username = $username;
		$this->secret = $secret;
		parent::__construct();
	}

	/**
	 * Parses the API-response and returns either a collection object or single item depending on the results.
	 *
	 * @param HttpResponse $originalResponse
	 * @return mixed
	 * @throws ServiceException
	 */
	protected function onResponseReceived(HttpResponse $originalResponse) {
		$response = json_decode($originalResponse->getResponse(), true);

		// Parse the results
		if(!is_array($response) || $response && isset($response['status'])) {
			$error = (isset($response['error'])) ? $response['error'] : 'API returned invalid response.';
			$status = (isset($response['status'])) ? $response['status'] : 0;

			throw new ServiceException($error, $status, $originalResponse);
		}

		// Check if the result is a collection of items
		if(isset($response['rows'])) {
			$result = $this->onCreateCollection();

			// If the item is an instance of IServiceCollection we can set the response
			if($result instanceof IServiceCollection) {
				$result->setResponse($originalResponse, $response);
			}

			$items = array();

			foreach($response['rows'] as $row) {
				$item = $this->onCreateItem();
				$item->setRow((object)$row);
				$items[] = $item;
			}

			$result->setRows($items);
			return $result;
		}

		// It wasn't a collection of items, so we just return a single item
		$item = $this->onCreateItem();
		$item->setRow((object)$response);
		return $item;
	}

	public function api($url = null, $method = self::METHOD_GET, array $data = array()) {

		$this->request->setOptions(array(
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false
		));

		$this->request->setTimeout(10000);

		$data = array_merge($this->request->getPostData(), $data);

		// Add authentication required by index-search
		$this->request->setBasicAuth($this->username, $this->secret);

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

}