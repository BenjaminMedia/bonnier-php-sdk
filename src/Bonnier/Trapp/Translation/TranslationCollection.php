<?php
namespace Bonnier\Trapp\Translation;

use Bonnier\HttpResponse;
use Bonnier\IndexSearch\IServiceCollection;
use Bonnier\RestCollection;

class TranslationCollection extends RestCollection implements IServiceCollection {

    protected $total;
    protected $skip;
    protected $limit;

    public function setResponse(HttpResponse $response, $formattedResponse) {
        $this->skip = $formattedResponse['skip'];
        $this->limit = $formattedResponse['limit'];
        $this->total = $formattedResponse['total'];
    }

    /* Filters start */

    public function sort($field) {
        $this->service->getRequest()->addPostData('sort', $field);
        return $this;
    }

    public function order($order) {
        $this->service->getRequest()->addPostData('order', $order);
        return $this;
    }

    public function locale($locale) {
        $this->service->getRequest()->addPostData('locale', $locale);
        return $this;
    }

    public function app($appId) {
        $this->service->getRequest()->addPostData('app_id', $appId);
        return $this;
    }

    public function state($state) {
        $this->service->getRequest()->addPostData('state', $state);
        return $this;
    }

    public function q($query) {
        $this->service->getRequest()->addPostData('q', $query);
        return $this;
    }

    public function skip($skip) {
        $this->service->getRequest()->addPostData('skip', $skip);
        return $this;
    }

    public function limit($limit) {
        $this->service->getRequest()->addPostData('limit', $limit);
        return $this;
    }

    public function filterOriginal($bool) {
        $this->service->getRequest()->addPostData('filter_original', $bool);
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
        $this->service->getRequest()->setPostData($data);
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }

}