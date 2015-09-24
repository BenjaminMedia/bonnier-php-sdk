<?php
namespace Bonnier\Shell;

use Bonnier\HttpResponse;

class ShellResponse {

    private $httpResponse;
    private $startTag;
    private $head;
    private $body;
    private $header;
    private $endTag;
    private $banners;

    public function __construct(HttpResponse $response) {
        $this->httpResponse = $response;

        $responseObject = json_decode($this->httpResponse->getResponse());

        $this->startTag = $responseObject->html->start_tag;
        $this->head = $responseObject->html->head;
        $this->body = $responseObject->html->body;
        $this->header = $responseObject->html->body->header;
        $this->endTag = $responseObject->html->end_tag;
        $this->banners = $responseObject->html->banners;
    }
    /**
     * @return string
     */
    public function getStartTag(){
        return $this->startTag;
    }

    /**
     * @return string
     */
    public function getHead(){
        return $this->head;
    }

    /**
     * @return \stdClass
     */
    public function getBody(){
        return $this->body;
    }

    /**
     * @return string
     */
    public function getHeader(){
        return $this->header;
    }

    /**
     * @return string
     */
    public function getEndTag(){
        return $this->endTag;
    }

    /**
     * @return string
     */
    public function getBanners(){
        return $this->banners;
    }

    /**
     * @return \Bonnier\HttpResponse
     */
    public function getHttpResponse() {
        return $this->httpResponse;
    }

}