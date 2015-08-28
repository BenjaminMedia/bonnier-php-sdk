<?php
namespace Bonnier;

abstract class RESTBase extends RESTServiceItem {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public static $METHODS = array(self::METHOD_GET, self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE);

    protected $serviceUrl;
    protected $postJson;

    /**
     * @var HttpRequest
     */
    protected $request;

    public function __construct() {
        $this->request = new HttpRequest();
    }

    abstract protected function onResponseCreate(HttpResponse $response);

    protected function getServiceUrl() {
        return $this->serviceUrl;
    }

    public function postJson($bool) {
        $this->postJson = $bool;
    }

    /**
     * @return HttpRequest
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @param string|null $url
     * @param string $method
     * @param array|NULL $data
     * @throws ServiceException
     * @return HttpResponse
     */
    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        if(!in_array($method, self::$METHODS)) {
            throw new ServiceException('Invalid request method');
        }

        $data = (is_array($data)) ? array_merge($this->request->getPostData(), $data) : $this->request->getPostData();

        $data['_method'] = $method;

        $postData = NULL;

        if($method == self::METHOD_GET && is_array($data)) {
            $url = $url . '?'.http_build_query($data);
        } else {
            $postData = $data;
            $this->request->addHeader('Content-length: ' . strlen($postData));
        }

        $apiUrl = rtrim($this->getServiceUrl(), '/') . '/' . $url;

        $this->request->setUrl($apiUrl);

        if($method != self::METHOD_GET) {
            $this->request->setPostData($postData);
        }

        $this->request->setMethod($method);

        $response = $this->request->execute(TRUE);

        // Trigger event
        return $this->onResponseCreate($response);
    }

}