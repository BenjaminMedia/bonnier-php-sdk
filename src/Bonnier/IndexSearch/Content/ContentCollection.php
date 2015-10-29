<?php
namespace Bonnier\IndexSearch\Content;
use Bonnier\HttpResponse;
use Bonnier\IndexSearch\IServiceCollection;
use Bonnier\RestCollection;

class ContentCollection extends RestCollection implements IServiceCollection {

    protected $total;
    protected $skip;
    protected $limit;
    protected $searchTime;

    public function setResponse(HttpResponse $response, $formattedResponse) {
        $this->searchTime = $formattedResponse['searchTime'];
        $this->skip = $formattedResponse['skip'];
        $this->limit = $formattedResponse['limit'];
        $this->total = $formattedResponse['total'];
    }

    public function query($query) {
        $this->service->getHttpRequest()->addPostData('q', $query);
        return $this;
    }

    public function sort($sort) {
        $this->service->getHttpRequest()->addPostData('sort', $sort);
        return $this;
    }

    public function order($order) {
        $this->service->getHttpRequest()->addPostData('order', $order);
        return $this;
    }

    public function filter($name, $value) {
        $this->service->getHttpRequest()->addPostData($name, $value);
        return $this;
    }

    public function dsl(array $dsl) {
        $this->service->getHttpRequest()->addPostData('dsl', json_encode($dsl));
        return $this;
    }

    public function skip($skip) {
        $this->service->getHttpRequest()->addPostData('skip', $skip);
        return $this;
    }

    public function limit($limit) {
        $this->service->getHttpRequest()->addPostData('limit', $limit);
        return $this;
    }

    public function meta($key, $value) {
        $this->service->getHttpRequest()->addPostData('meta_' . strtolower($key), $value);
        return $this;
    }

    public function app($appCode) {
        $this->service->getHttpRequest()->addPostData('app_code', $appCode);
        return $this;
    }

    public function brand($siteCode) {
        $this->service->getHttpRequest()->addPostData('brand_code', $siteCode);
        return $this;
    }

    public function contentType($type) {
        $this->service->getHttpRequest()->addPostData('content_type', $type);
        return $this;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getSearchTime() {
        return $this->searchTime;
    }

    public function getSkip() {
        return $this->skip;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }
}