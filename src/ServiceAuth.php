<?php
namespace BonnierPHPSDK;

use BonnierPHPSDK\Service\ServiceBase;

class ServiceAuth extends ServiceBase {

    const TYPE = 'auth';

    public function __construct($secret) {
        parent::__construct($secret, self::TYPE);
    }

    public function check($role) {
        return $this->api($role);
    }

}