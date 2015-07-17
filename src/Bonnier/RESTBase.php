<?php
namespace Bonnier;

use Bonnier\IndexDB\ServiceResult;

abstract class RESTBase {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public static $METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    protected $username;
    protected $secret;
    protected $type;
    protected $serviceUrl;
    protected $postJson;

    public function __construct($username, $secret, $type) {
        if (!function_exists('curl_init')) {
            throw new \Exception('This service requires the CURL PHP extension.');
        }

        if (!function_exists('json_decode')) {
            throw new \Exception('This service requires the JSON PHP extension.');
        }

        $this->username = $username;
        $this->secret = $secret;
        $this->type = $type;
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

        $apiUrl = sprintf($this->serviceUrl, $this->type) . $url;

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

        $class = get_called_class();

        if(isset($response['id'])) {
            $item = new $class($this->username, $this->secret, $this->type);
            $item->row = (object)$response;
            return $item;
        }

        if(isset($response['rows'])) {
            $result = new \Bonnier\ServiceResult($this->username, $this->secret, $this->type);
            $result->setResponse($response);

            $items = array();

            foreach($response['rows'] as $row) {
                $item = new ServiceItem($this->username, $this->secret, $this->type);
                $item->row = (object)$row;
                $items[] = $item;
            }

            $result->rows = $items;
            return $result;
        }
        $item = new $class($this->username, $this->secret, $this->type);
        $item->setRow((object)$response);
        return $item;
    }

    public function postJson($bool) {
        $this->postJson = $bool;
    }
}