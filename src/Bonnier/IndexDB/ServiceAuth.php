<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceResult;

class ServiceAuth extends IndexSearchBase {

    const TYPE = 'auth';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * @param $role
     *
     * @return IndexServiceItem
     * @throws \Bonnier\ServiceException
     */
    public function check($role) {
        return $this->api($role);
    }

}