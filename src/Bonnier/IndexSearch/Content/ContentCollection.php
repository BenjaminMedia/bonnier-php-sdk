<?php
namespace Bonnier\IndexSearch\Content;
use Bonnier\HttpResponse;
use Bonnier\RestCollection;

class ContentCollection extends RestCollection {

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
        $this->request->addPostData('q', $query);
        return $this;
    }

    public function sort($sort) {
        $this->request->addPostData('sort', $sort);
        return $this;
    }

    public function order($order) {
        $this->request->addPostData('order', $order);
        return $this;
    }

    public function filter($name, $value) {
        $this->request->addPostData($name, $value);
        return $this;
    }

    public function dsl(array $dsl) {
        $this->request->addPostData('dsl', json_encode($dsl));
        return $this;
    }

    public function skip($skip) {
        $this->request->addPostData('skip', $skip);
        return $this;
    }

    public function limit($limit) {
        $this->request->addPostData('limit', $limit);
        return $this;
    }

    public function meta($key, $value) {
        $this->request->addPostData('meta_' . strtolower($key), $value);
        return $this;
    }

    public function app($appCode) {
        $this->request->addPostData('app_code', $appCode);
        return $this;
    }

    public function site($siteCode) {
        $this->request->addPostData('site_code', $siteCode);
        return $this;
    }

    public function contentType($type) {
        $this->request->addPostData('content_type', $type);
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