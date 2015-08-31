<?php
namespace Bonnier\IndexSearch;

use Bonnier\ServiceResult;

class ServiceApplication extends ServiceBase {

    const TYPE = 'application';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * Get queryable collection
     *
     * @return \Bonnier\IndexSearch\REST\RESTCollection
     * @throws \Bonnier\ServiceException
     */
    public function getCollection() {
        return $this->onCreateCollection();
    }

    /**
     * Get item by id
     *
     * @param int $id
     * @return IndexServiceItem
     * @throws \Bonnier\ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

}