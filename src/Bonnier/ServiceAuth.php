<?php
namespace Bonnier;

use Bonnier\Service\ServiceBase;
use Bonnier\Service\ServiceItem;

class ServiceAuth extends ServiceBase {

    const TYPE = 'auth';

    public function __construct($secret) {
        parent::__construct($secret, self::TYPE);
    }

    public function check($role) {
        $item = new ServiceItem($this->secret, $this->type);
        return $item->api($role);
    }

}