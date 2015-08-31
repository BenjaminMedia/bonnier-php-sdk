<?php
namespace Bonnier\IndexSearch;

class ServiceContentType extends ServiceBase {

    const TYPE = 'contenttype';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
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
     * @return \Bonnier\IndexSearch\REST\RESTCollection
     */
    public function getCollection() {
        return $this->onCreateCollection();
    }

    /**
     * Get single item by id.
     *
     * @param $id
     * @throws \Bonnier\ServiceException
     * @return \Bonnier\IndexSearch\REST\RESTItem
     */
    public function getById($id) {
        return $this->api($id);
    }

}