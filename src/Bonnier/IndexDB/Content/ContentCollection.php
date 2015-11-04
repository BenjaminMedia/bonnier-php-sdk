<?php
namespace Bonnier\IndexDB\Content;
use Bonnier\IndexDB\ServiceContent;

class ContentCollection extends ServiceContent {

    protected $total;
    protected $skip;
    protected $limit;
    protected $searchTime;
    protected $_data = array();
    protected $rows;

    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        $data = (is_array($data)) ? array_merge($this->_data, $data) : $this->_data;
        return parent::api($url, $method, $data);
    }

    public function execute() {
        return $this->api();
    }

    public function setResponse($response) {
        $this->searchTime = $response['searchTime'];
        $this->skip = $response['skip'];
        $this->limit = $response['limit'];
        $this->total = $response['total'];
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

    public function meta($key, $value) {
        $this->_data['meta.' . strtolower($key)] = $value;
        return $this;
    }

    public function app($appCode) {
        $this->_data['app_code'] = $appCode;
        return $this;
    }

    public function brand($brandCode) {
        $this->_data['brand_code'] = $brandCode;
        return $this;
    }

    /**
     * @param $siteCode
     * @return self
     * @depricated This method is depricated, please use brand method instead.
     */
    public function site($siteCode) {
        return $this->brand($siteCode);
    }

    public function contentType($type) {
        $this->_data['content_type'] = $type;
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

    public function getRows() {
        return $this->rows;
    }

    public function setRows($rows) {
        $this->rows = $rows;
    }
}