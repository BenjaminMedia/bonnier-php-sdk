<?php
namespace Bonnier\IndexDB;
use Bonnier\HttpResponse;
use Bonnier\RESTBase;
use Bonnier\ServiceException;

class IndexSearchBase extends RESTBase {

	protected $username;
	protected $secret;

	protected $development;
	protected $type;

	public function __construct($username, $secret, $type = '') {
		parent::__construct();
		$this->username = $username;
		$this->secret = $secret;
		$this->type = $type;
	}

	// Events
	protected function onCreateResult() {
		return new IndexServiceResult($this->username, $this->secret, $this->type);
	}

	protected function onCreateItem() {
		$self = get_called_class();
		$item = new $self($this->username, $this->secret, $this->type);
		$item->setDevelopment($this->development);
		return $item;
	}

	protected function onResponseCreate(HttpResponse $originalResponse) {
		$response = json_decode($originalResponse->getResponse(), TRUE);

		// Parse the results
		if(!is_array($response) || $response && isset($response['status'])) {
			$error = (isset($response['error'])) ? $response['error'] : 'API returned invalid response.';
			$status = (isset($response['status'])) ? $response['status'] : 0;

			throw new ServiceException($error, $status, $originalResponse);
		}

		// Check if the result is a collection of items
		if(isset($response['rows'])) {
			$result = $this->onCreateResult();

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

		// We can't determinate weather this is a single item or a collection, so we just return a single item
		$item = $this->onCreateItem();
		$item->setRow((object)$response);
		return $item;
	}

	protected function getServiceUrl() {
		if($this->development) {
			$this->serviceUrl = 'https://staging-indexdb.whitealbum.dk/api/v1/%1$s/';
		} else {
			$this->serviceUrl = 'https://indexdb.whitealbum.dk/api/v1/%1$s/';
		}

		return sprintf($this->serviceUrl, $this->type);
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		return $this;
	}

	/**
	 * @param string|null $url
	 * @param string $method
	 * @param array|NULL $data
	 * @throws ServiceException
	 * @return ServiceResult
	 */
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
		return parent::api($url, $method, $data);
	}

}