<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceResult;

class ServiceApplication extends IndexSearchBase {

    const TYPE = 'application';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * @return IndexServiceResult
     * @throws \Bonnier\ServiceException
     */
    public function getCollection() {
        return $this->onCreateResult();
    }

    /**
     * @param $id
     *
     * @return IndexServiceItem
     * @throws \Bonnier\ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

}