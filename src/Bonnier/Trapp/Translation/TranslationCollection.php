<?php
namespace Bonnier\Trapp\Translation;

use Pecee\Http\HttpResponse;
use Bonnier\IndexSearch\IServiceCollection;
use Pecee\Http\Rest\RestCollection;

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
        $this->service->getHttpRequest()->addPostData('sort', $field);
        return $this;
    }

    public function order($order) {
        $this->service->getHttpRequest()->addPostData('order', $order);
        return $this;
    }

    public function locale($locale) {
        $this->service->getHttpRequest()->addPostData('locale', $locale);
        return $this;
    }

    public function app($appId) {
        $this->service->getHttpRequest()->addPostData('app_id', $appId);
        return $this;
    }

    public function state($state) {
        $this->service->getHttpRequest()->addPostData('state', $state);
        return $this;
    }

    public function q($query) {
        $this->service->getHttpRequest()->addPostData('q', $query);
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

    public function filterOriginal($bool) {
        $this->service->getHttpRequest()->addPostData('filter_original', $bool);
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
        $this->service->getHttpRequest()->setPostData($data);
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->total;
    }

    public function dsl(array $dsl) {
        $this->service->getHttpRequest()->addPostData('dsl', json_encode($dsl));
        return $this;
    }

    public function metaOrder($field, $sort = 'asc') {
        if(!in_array(strtolower($sort), array('asc', 'desc'))) {
            throw new \InvalidArgumentException('Invalid sort option');
        }

        $dsl = [
            'sort' => [
                'meta.' . $field => $sort,
            ]
        ];

        $currentDsl = $this->service->getHttpRequest()->getPostData();
        $currentDsl = (isset($currentDsl['dsl'])) ? json_decode($currentDsl['dsl']) : array();
        $this->dsl(array_merge($currentDsl, $dsl));

        return $this;
    }

}