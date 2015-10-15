<?php
namespace Bonnier;

class RestBase {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public static $METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    protected $serviceUrl;

    /**
     * @var HttpRequest
     */
    protected $httpRequest;

    public function __construct() {
        $this->httpRequest = new HttpRequest();
    }

    public function getServiceUrl() {
        return $this->serviceUrl;
    }

    public function setServiceUrl($serviceUrl) {
        $this->serviceUrl = $serviceUrl;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest() {
        return $this->httpRequest;
    }

    /**
     * Execute api call.
     *
     * @param string|null $url
     * @param string $method
     * @param array|null $data
     * @throws ServiceException
     * @return HttpResponse|mixed
     */
    public function api($url = null, $method = self::METHOD_GET, array $data = array()) {
        if(!in_array($method, self::$METHODS)) {
            throw new ServiceException('Invalid request method');
        }

        $data = array_merge($this->httpRequest->getPostData(), $data);
        $data['_method'] = $method;

        if($method == self::METHOD_GET && is_array($data)) {
            $url .= '?'.http_build_query($data);
        }

        $apiUrl = rtrim($this->getServiceUrl(), '/') . ($url ? '/' . $url : '');

        $this->httpRequest->setUrl($apiUrl);

        if($method != self::METHOD_GET) {
            $this->httpRequest->setPostData($data);
        }

        $this->httpRequest->setMethod($method);

        $response = $this->httpRequest->execute(true);

        // Reset request (headers, post-data etc)
        $this->httpRequest->reset();

        return $response;
    }

}