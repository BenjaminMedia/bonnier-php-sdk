<?php
namespace Bonnier\IndexSearch;

use Bonnier\HttpResponse;
use Bonnier\RestCollection;
use Bonnier\RestItem;
use Bonnier\ServiceException;

abstract class ServiceRestBase extends \Bonnier\RestBase {

	protected $username;
	protected $secret;

	/**
	 * @var IServiceEventListener
	 */
	protected $serviceEventListener;

	public function __construct($username, $secret) {
		$this->username = $username;
		$this->secret = $secret;
		parent::__construct();
	}

	// Events that can be overwritten, if needed
	protected function onCreateCollection() {
		if($this->serviceEventListener) {
			return $this->serviceEventListener->onCreateCollection();
		}

		$result = new RestCollection($this->username, $this->secret, $this->type);
		$result->setDevelopment($this->development);
		return $result;
	}

	protected function onCreateItem() {
		if($this->serviceEventListener) {
			return $this->serviceEventListener->onCreateItem();
		}

		$item = new RestItem($this->username, $this->secret, $this->type);
		$item->setDevelopment($this->development);
		return $item;
	}

	/**
	 * Parses the API-response and returns either a collection object or single item depending on the results.
	 *
	 * @param HttpResponse $originalResponse
	 * @return mixed
	 * @throws ServiceException
	 */
	protected function onResponseReceived(HttpResponse $originalResponse) {
		$response = json_decode($originalResponse->getResponse(), TRUE);

		// Parse the results
		if(!is_array($response) || $response && isset($response['status'])) {
			$error = (isset($response['error'])) ? $response['error'] : 'API returned invalid response.';
			$status = (isset($response['status'])) ? $response['status'] : 0;

			throw new ServiceException($error, $status, $originalResponse);
		}

		// Check if the result is a collection of items
		if(isset($response['rows'])) {
			$result = $this->onCreateCollection();

			$result->setResponse($originalResponse, $response);
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

	public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {

		$this->request->setOptions(array(
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_SSL_VERIFYPEER => FALSE
		));

		$this->request->setTimeout(10000);

		$data = (is_array($data)) ? array_merge($this->request->getPostData(), $data) : $this->request->getPostData();

		// Add authentication required by index-search
		$this->request->addHeader('Authorization: Basic ' . base64_encode(sprintf('%s:%s', $this->username, $this->secret)));

		// Execute the API-call
		return $this->onResponseReceived( parent::api($url, $method, $data) );
	}

	/**
	 * @return IServiceEventListener
	 */
	public function getServiceEventListener() {
		return $this->serviceEventListener;
	}

	/**
	 * @param IServiceEventListener $serviceEventListener
	 */
	public function setServiceEventListener(IServiceEventListener $serviceEventListener) {
		$this->serviceEventListener = $serviceEventListener;
	}

}