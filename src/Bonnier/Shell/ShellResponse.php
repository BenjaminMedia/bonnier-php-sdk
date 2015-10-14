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
    private $logos;

    public function __construct(HttpResponse $response) {
        $this->httpResponse = $response;

        $responseObject = json_decode($this->httpResponse->getResponse());

        $this->startTag = $responseObject->html->start_tag;
        $this->head = $responseObject->html->head;
        $this->body = $responseObject->html->body;
        $this->header = $responseObject->html->body->header;
        $this->endTag = $responseObject->html->end_tag;
        $this->banners = $responseObject->html->banners;
        $this->logos = $responseObject->logos;
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

    /**
     * Returns two strings in an array. Each string contains an url
     * referencing the location of the stored images. The images
     * provided are 'logo_standard' and 'logo_unicolor_white'
     *
     * @return array
     */
    public function getLogos() {
        return $this->logos;
    }

}