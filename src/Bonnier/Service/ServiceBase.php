<?php
namespace Bonnier\Service;

abstract class ServiceBase {

    const SERVICE_URL = 'https://indexdb.whitealbum.dk/api/%1$s/';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public static $METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    protected $username;
    protected $secret;
    protected $type;

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

        $apiUrl = sprintf(self::SERVICE_URL, $this->type) . $url;


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode(sprintf('%s:%s', $this->username, $this->secret))));
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 10000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($method != self::METHOD_GET) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        $response = json_decode(curl_exec($ch), TRUE);

        if(!is_array($response) || $response && isset($response['status'])) {
            throw new ServiceException($response['error'], $response['status']);
        }

        $class = get_called_class();

        if(isset($response['id'])) {
            $item = new $class($this->username, $this->secret, $this->type);
            $item->row = (object)$response;
            return $item;
        }

        if(isset($response['rows'])) {

            $result = new $class($this->username, $this->secret, $this->type);
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

        $item = new ServiceItem($this->username, $this->secret, $this->type);
        $item->setRow((object)$response);
        return $item;
    }
}