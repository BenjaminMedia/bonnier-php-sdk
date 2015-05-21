<?php
namespace Bonnier;

class Service {

    const SERVICE_URL = 'http://bonnier.index.search/api/content/';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public static $METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    protected $secret;

    public function __construct($secret) {
        if (!function_exists('curl_init')) {
            throw new \Exception('This service requires the CURL PHP extension.');
        }
        if (!function_exists('json_decode')) {
            throw new \Exception('This service requires the JSON PHP extension.');
        }

        $this->secret = $secret;
    }

    /**
     * Get queryable service result
     * @return Service\ServiceResult
     */
    public function get() {
        return new \Bonnier\Service\ServiceResult($this->secret);
    }

    /**
     * Get single item by id
     * @param $id
     * @return Service\ServiceItem
     * @throws Service\ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

    /**
     * @param string|null $url
     * @param string $method
     * @param array|NULL $data
     * @throws ServiceException
     * @return \Bonnier\Service\ServiceResult
     */
    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        if(!in_array($method, self::$METHODS)) {
            throw new \Bonnier\Service\ServiceException('Invalid request method');
        }

        $data['_method'] = $method;

        $postData = NULL;

        if($method == self::METHOD_GET && is_array($data)) {
            $url = $url . '?'.http_build_query($data);
        } else {
            $postData = $data;
        }

        $ch = curl_init(self::SERVICE_URL . $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Auth-secret: ' . $this->secret));
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($method != self::METHOD_GET) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        $response = @json_decode(curl_exec($ch), TRUE);

        if(!$response || $response && isset($response['error'])) {
            throw new \Bonnier\Service\ServiceException($response['error'], $response['errorCode']);
        }

        if(isset($response['_source'])) {
            $item = new \Bonnier\Service\ServiceItem($this->secret, (object)$response['_source']);
            $item->id = $response['_id'];
            $item->index = $response['_index'];
            $item->type = $response['_type'];
            return $item;
        }

        if(isset($response['hits'])) {
            $result = new \Bonnier\Service\ServiceResult($this->secret);
            $result->timedOut = $response['timed_out'];
            $result->took = $response['took'];

            $result->maxScore = $response['hits']['max_score'];

            $items = array();

            if(isset($response['hits']['hits'])) {
                foreach($response['hits']['hits'] as $hit) {
                    $item = new \Bonnier\Service\ServiceItem($this->secret, (object)$hit['_source']);
                    $item->id = $hit['_id'];
                    $item->index = $hit['_index'];
                    $item->score = $hit['_score'];
                    $item->type = $hit['_type'];
                    $items[] = $item;
                }
            }

            $result->hits = $items;
            return $result;
        }

        return $response;
    }
}