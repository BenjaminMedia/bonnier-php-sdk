<?php
namespace Bonnier\Service;
class ServiceContentResult extends ServiceResult {

    public $total;
    public $skip;
    public $limit;
    public $searchTime;

    public function setResponse($response) {
        $this->searchTime = $response['searchTime'];
        $this->skip = $response['skip'];
        $this->limit = $response['limit'];
        parent::setResponse($response);
    }

    public function query($query) {
        $this->_data['q'] = $query;
        return $this;
    }

    public function sort($sort) {
        $this->_data['sort'] = $sort;
        return $this;
    }

    public function order($order) {
        $this->_data['order'] = $order;
        return $this;
    }

    public function filter($name, $value) {
        $this->_data[$name] = $value;
        return $this;
    }

    public function dsl(array $dsl) {
        $this->_data['dsl'] = json_encode($dsl);
        return $this;
    }

    public function skip($skip) {
        $this->_data['skip'] = $skip;
        return $this;
    }

    public function limit($limit) {
        $this->_data['limit'] = $limit;
        return $this;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function getSearchTime() {
        return $this->searchTime;
    }

    public function setSearchTime($searchTime) {
        $this->searchTime = $searchTime;
    }

    public function getSkip() {
        return $this->skip;
    }

    public function getLimit() {
        return $this->limit;
    }
}