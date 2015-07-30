<?php
namespace Bonnier;

// TODO: Make this class more transparent by creating methods that can be overwritten
// onCreateItem - onCreateResult etc.

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

    public function __construct($username, $secret) {
        if (!function_exists('curl_init')) {
            throw new \Exception('This service requires the CURL PHP extension.');
        }

        if (!function_exists('json_decode')) {
            throw new \Exception('This service requires the JSON PHP extension.');
        }

        $this->username = $username;
        $this->secret = $secret;
    }

    // Events
    protected function onCreateItem() {
        return new ServiceItem($this->username, $this->secret);
    }

    protected function onCreateResult() {
        return new ServiceResult($this->username, $this->secret);
    }

    protected function getServiceUrl() {
        return $this->serviceUrl;
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

        $postData = NULL;

        if($method == self::METHOD_GET && is_array($data)) {
            $url = $url . '?'.http_build_query($data);
        } else {
            $postData = $data;
        }

        $apiUrl = $this->getServiceUrl() . $url;

        $headers = array('Authorization: Basic ' . base64_encode(sprintf('%s:%s', $this->username, $this->secret)));
        if($this->postJson && count($postData) > 0) {
            $headers[] = 'Content-type: application/json';
            $headers[] = 'Content-length: ' . strlen(json_encode($postData));
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 10000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($method != self::METHOD_GET) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            if($this->postJson) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            }
        }

        $response = json_decode(curl_exec($ch), TRUE);

        if(!is_array($response) || $response && isset($response['status'])) {
            $error = (isset($response['error'])) ? $response['error'] : 'API response error: ' . $apiUrl;
            $status = (isset($response['status'])) ? $response['status'] : 0;
            throw new ServiceException($error, $status);
        }

        if(isset($response['rows'])) {
            $result = $this->onCreateResult();

            if(!($result instanceof ServiceResult)) {
                throw new ServiceException('Unknown item type - must be an instance of Service Item');
            }

            $result->setResponse($response);
            $items = array();

            foreach($response['rows'] as $row) {
                $item = $this->onCreateItem();

                if(!($item instanceof ServiceItem)) {
                    throw new ServiceException('Unknown item type - must be an instance of Service Item');
                }

                $item->setRow((object)$row);
                $items[] = $item;
            }

            $result->setRows($items);
            return $result;
        }

        // We can't determinate weather this is a single item or a collection, so we just return a single item
        $item = $this->onCreateItem();

        if(!($item instanceof ServiceItem)) {
            throw new ServiceException('Unknown item type - must be an instance of Service Item');
        }

        $item->setResponse($response);
        $item->setRow((object)$response);
        return $item;
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
}