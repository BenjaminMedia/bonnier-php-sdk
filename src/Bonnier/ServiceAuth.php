<?php
namespace Bonnier;

use Bonnier\Service\ServiceBase;
use Bonnier\Service\ServiceItem;

class ServiceAuth extends ServiceBase {

    const TYPE = 'auth';

    public function __construct($username, $secret) {
        parent::__construct($username, $secret, self::TYPE);
    }

    public function check($role) {
        $item = new ServiceItem($this->username, $this->secret, $this->type);
        return $item->api($role);
    }

}