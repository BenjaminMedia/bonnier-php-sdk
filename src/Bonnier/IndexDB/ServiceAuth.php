<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceResult;

class ServiceAuth extends IndexDBBase {

    const TYPE = 'auth';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * @param $role
     *
     * @return ServiceResult
     * @throws \Bonnier\ServiceException
     */
    public function check($role) {
        return $this->api($role);
    }

}