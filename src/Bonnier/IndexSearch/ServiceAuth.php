<?php
namespace Bonnier\IndexSearch;

use Bonnier\ServiceResult;

class ServiceAuth extends ServiceBase {

    const TYPE = 'auth';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    /**
     * Check if you have access to given role
     *
     * @param string $role
     * @return IndexServiceItem
     * @throws \Bonnier\ServiceException
     */
    public function check($role) {
        return $this->api($role);
    }

}