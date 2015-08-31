<?php
namespace Bonnier\Trapp;

use Bonnier\HttpResponse;
use Bonnier\RESTBase;

abstract class TrappBase extends RESTBase {

	protected $development;
	protected $type;

	public function __construct($username, $secret, $type) {
		parent::__construct($username, $secret);
		$this->type = $type;
	}

	protected function onCreateItem() {
		$self = get_called_class();
		$item = new $self($this->username, $this->secret, $this->type);
		$item->setDevelopment($this->development);
		return $item;
	}

	// TODO: this thing is identical to the one used in IndexSearchBase as the api's are identical.. could be optimized
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

	/*protected function getServiceUrl() {
		if($this->development) {
			$this->serviceUrl = 'https://local.trapp/api/v1/';
		} else {
			$this->serviceUrl = 'https://trapp.whitealbum.dk/api/v1/';
		}

		return sprintf($this->serviceUrl, $this->type);
	}*/

	// TODO: Add production/staging enviroment when ready (see above)

	protected function getServiceUrl() {
		return 'http://staging-trapp.whitealbum.dk/api/v1/' . $this->type;
	}

	public function setDevelopment($bool) {
		$this->development = $bool;
		return $this;
	}

}