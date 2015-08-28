<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceException;
use Bonnier\ServiceItem;
use Bonnier\ServiceResult;

class ServiceContentType extends IndexSearchBase {

    const TYPE = 'contenttype';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    public function skip($skip) {
        $this->_data['skip'] = $skip;
        return $this;
    }

    public function limit($limit) {
        $this->_data['limit'] = $limit;
        return $this;
    }

    /**
     * Get queryable service result
     * @return ServiceResult
     */
    public function getCollection() {
        return $this->api();
    }

    /**
     * Get single item by id
     * @param $id
     * @return ServiceItem
     * @throws ServiceException
     */
    public function getById($id) {
        return $this->api($id);
    }

}