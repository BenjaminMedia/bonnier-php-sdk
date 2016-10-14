<?php
namespace Bonnier\Shell;

use GuzzleHttp\Psr7\Response;

class ShellResponse {

    /* @var \GuzzleHttp\Psr7\Response $httpResponse */
    private $httpResponse;
    private $startTag;
    private $head;
    private $body;
    private $header;
    private $footer;
    private $endTag;
    private $banners;
    private $logos;
    private $requestUri;

    public function __construct(Response $response, $requestUri) {
        $this->httpResponse = $response;

        $responseObject = json_decode($response->getBody()->getContents());

        $this->startTag = $responseObject->html->start_tag;
        $this->head = $responseObject->html->head;
        $this->body = $responseObject->html->body;
        $this->header = $responseObject->html->body->header;
        $this->footer = $responseObject->html->body->footer;
        $this->endTag = $responseObject->html->end_tag;
        $this->banners = isset($responseObject->html->banners) ? $responseObject->html->banners : $responseObject->html->ad;
        $this->logos = $responseObject->logos;
        $this->requestUri = $requestUri;
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
    public function getFooter(){
        return $this->footer;
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
     * @return \GuzzleHttp\Psr7\Response
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

    /**
     * Returns the uri that was requested
     *
     * @return String $requestUri
     */
    public function getRequestUri() {
        return $this->requestUri;
    }

}