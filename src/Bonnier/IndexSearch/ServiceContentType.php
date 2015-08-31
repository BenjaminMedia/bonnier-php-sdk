<?php
namespace Bonnier\IndexSearch;

use Bonnier\RestItem;

class ServiceContentType extends RestItem {

    const TYPE = 'contenttype';

    public function __construct($username, $secret) {
        parent::__construct(new ServiceBase($username, $secret, self::TYPE));
    }

    public function skip($skip) {
        $this->request->addPostData('skip', $skip);
        return $this;
    }

    public function limit($limit) {
        $this->request->addPostData('limit', $limit);
        return $this;
    }

    /**
     * Get queryable service result.
     *
     * @return \Bonnier\RESTCollection
     */
    public function getCollection() {
        return $this->onCreateCollection();
    }

    /**
     * Get single item by id.
     *
     * @param $id
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\RESTItem
     */
    public function getById($id) {
        return $this->api($id);
    }

    public function setDevelopment($bool) {
        $this->service->setDevelopment($bool);
        return $this;
    }

}