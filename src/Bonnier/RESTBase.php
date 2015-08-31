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
    protected $request;

    public function __construct() {
        $this->request = new HttpRequest();
    }

    protected function getServiceUrl() {
        return $this->serviceUrl;
    }

    /**
     * @return HttpRequest
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Execute api call.
     *
     * Return type will be whats defined in the event $this->onResponseReceived().
     *
     * @param string|null $url
     * @param string $method
     * @param array|NULL $data
     * @throws ServiceException
     * @return mixed
     */
    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        if(!in_array($method, self::$METHODS)) {
            throw new ServiceException('Invalid request method');
        }

        $data = (is_array($data)) ? array_merge($this->request->getPostData(), $data) : $this->request->getPostData();
        $data['_method'] = $method;

        if($method == self::METHOD_GET && is_array($data)) {
            $url = $url . '?'.http_build_query($data);
        } else {
            $this->request->addHeader('Content-length: ' . strlen(http_build_query($data)));
        }

        $apiUrl = rtrim($this->getServiceUrl(), '/') . '/' . $url;

        $this->request->setUrl($apiUrl);

        if($method != self::METHOD_GET) {
            $this->request->setPostData($data);
        }

        $this->request->setMethod($method);

        $response = $this->request->execute(TRUE);

        // Reset request (headers, post-data etc)
        $this->request->reset();

        return $response;
    }

}