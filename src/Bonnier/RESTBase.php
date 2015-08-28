<?php
namespace Bonnier;

abstract class RESTBase {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public static $METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    protected $username;
    protected $secret;
    protected $serviceUrl;
    protected $postJson;
    protected $response;
    protected $originalResponse;

    protected $_data = array();

    public function __construct($username, $secret) {
        $this->username = $username;
        $this->secret = $secret;
        $this->_data = array();
    }

    // Events
    protected function onCreateResult() {
        return new ServiceResult($this->username, $this->secret);
    }

    protected function onCreateItem() {
        return new ServiceItem($this->username, $this->secret);
    }

    protected function getServiceUrl() {
        return $this->serviceUrl;
    }

    public function postJson($bool) {
        $this->postJson = $bool;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($response) {
        $this->response = $response;
    }

    /**
     * @return HttpResponse
     */
    public function getOriginalResponse() {
        return $this->originalResponse;
    }

    /**
     * @param HttpResponse $originalResponse
     */
    public function setOriginalResponse($originalResponse) {
        $this->originalResponse = $originalResponse;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * @param array $data
     */
    public function setData($data) {
        $this->_data = $data;
    }

    /**
     * @param string|null $url
     * @param string $method
     * @param array|NULL $data
     * @throws ServiceException
     * @return ServiceResult
     */
    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        if(!in_array($method, self::$METHODS)) {
            throw new ServiceException('Invalid request method');
        }

        $data['_method'] = $method;

        $data = (is_array($data)) ? array_merge($this->_data, $data) : $this->_data;

        $postData = NULL;

        if($method == self::METHOD_GET && is_array($data)) {
            $url = $url . '?'.http_build_query($data);
        } else {
            $postData = $data;
        }

        $apiUrl = rtrim($this->getServiceUrl(), '/') . '/' . $url;

        $request = new HttpRequest($apiUrl);
        $request->setTimeout(10000);

        $request->setOptions(array(
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE
        ));

        $request->addHeader('Authorization: Basic ' . base64_encode(sprintf('%s:%s', $this->username, $this->secret)));
        if($this->postJson && count($postData) > 0) {
            $request->addHeader('Content-type: application/json');
            $request->addHeader('Content-length: ' . strlen(json_encode($postData)));
        }

        if($method != self::METHOD_GET) {
            $request->setPostData(($this->postJson) ? json_encode($postData) : http_build_query($postData));
        }

        $request->setMethod($method);
        $originalResponse = $request->execute(TRUE);

        $response = json_decode($originalResponse->getResponse(), TRUE);

        if(!is_array($response) || $response && isset($response['status'])) {
            $error = (isset($response['error'])) ? $response['error'] : 'API response error: ' . $apiUrl;
            $status = (isset($response['status'])) ? $response['status'] : 0;

            throw new ServiceException($error, $status, $originalResponse);
        }

        if(isset($response['rows'])) {
            $result = $this->onCreateResult();

            $result->setResponse($response);
            $result->setOriginalResponse($originalResponse);
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
        $item->setResponse($response);
        $item->setOriginalResponse($originalResponse);
        $item->setRow((object)$response);
        return $item;
    }

}