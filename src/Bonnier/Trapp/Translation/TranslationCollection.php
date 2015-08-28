<?php
namespace Bonnier\Trapp\Translation;

use Bonnier\HttpResponse;
use Bonnier\Trapp\ServiceTranslation;

class TranslationCollection extends ServiceTranslation {

    protected $total;
    protected $skip;
    protected $limit;
    public $rows;

    // TODO: Do advanced logic here

    public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL) {
        $data = (is_array($data)) ? array_merge($this->_data, $data) : $this->_data;
        return parent::api($url, $method, $data);
    }

    public function execute() {
        return $this->api();
    }

    public function setResponse(HttpResponse $response, $formattedResponse) {
        $this->skip = $formattedResponse['skip'];
        $this->limit = $formattedResponse['limit'];
        $this->total = $formattedResponse['total'];
    }

    /* Filters start */

    public function sort($field) {
        $this->request->addPostData('sort', $field);
        return $this;
    }

    public function order($order) {
        $this->request->addPostData('order', $order);
        return $this;
    }

    public function locale($locale) {
        $this->request->addPostData('locale', $locale);
        return $this;
    }

    public function app($appId) {
        $this->request->addPostData('app_id', $appId);
        return $this;
    }

    public function state($state) {
        $this->request->addPostData('state', $state);
        return $this;
    }

    public function q($query) {
        $this->request->addPostData('q', $query);
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

    public function filterOriginal($bool) {
        $this->request->addPostData('filter_original', $bool);
        return $this;
    }

    /* Filters end */

    public function getSkip() {
        return $this->skip;
    }

    public function getLimit() {
        return $this->limit;
    }

    /**
     * @param $data
     * @depricated Warning this method is depricated, use $this->getRequest->addPostData() instead.
     */
    public function setData($data) {
        $this->request->setPostData($data);
    }

    public function getRows() {
        return $this->rows;
    }

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }

}