<?php
namespace Bonnier\Service;

abstract class ServiceBase {

    const SERVICE_URL = 'http://bonnierindexdb.pecee.dk/api/%1$s/';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public static $METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    protected $secret;
    protected $type;

    public function __construct($secret, $type) {
        if (!function_exists('curl_init')) {
            throw new \Exception('This service requires the CURL PHP extension.');
        }
        if (!function_exists('json_decode')) {
            throw new \Exception('This service requires the JSON PHP extension.');
        }

        $this->secret = $secret;
        $this->type = $type;
    }

    /**
     * Get queryable service result
     * @return ServiceResult
     */
    public function get() {
        return new ServiceResult($this->secret, $this->type);
    }

    /**
     * Get single item by id
     * @param $id
     * @return ServiceItem
     * @throws ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

    /**
     * Save item
     * @param \stdClass $row
     * @return $this
     */
    public function save(\stdClass $row) {
        $item = new ServiceItem($this->secret, $this->type);
        $item->row = $row;
        return $item->save();
    }

    public function delete($id) {
        return $this->api($id, self::METHOD_DELETE);
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

        $ch = curl_init(sprintf(self::SERVICE_URL, $this->type) . $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Auth-secret: ' . $this->secret));
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($method != self::METHOD_GET) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        $response = @json_decode(curl_exec($ch), TRUE);

        if(!$response || $response && isset($response['status'])) {
            throw new ServiceException($response['error'], $response['status']);
        }

        if(isset($response['id'])) {
            $item = new ServiceItem($this->secret, $this->type);
            $item->row = (object)$response;
            return $item;
        }

        if(isset($response['rows'])) {
            $result = new ServiceResult($this->secret, $this->type);
            $result->searchTime = $response['searchTime'];
            $result->skip = $response['skip'];
            $result->limit = $response['limit'];

            $items = array();

            if(isset($response['rows'])) {
                foreach($response['rows'] as $row) {
                    $item = new ServiceItem($this->secret, $this->type);
                    $item->row = (object)$row;
                    $items[] = $item;
                }
            }

            $result->rows = $items;
            return $result;
        }

        return $response;
    }
}