<?php
namespace Bonnier;

use Bonnier\Service\ServiceBase;

class ServiceContent extends ServiceBase {

    const TYPE = 'content';

    public function __construct($secret) {
        parent::__construct($secret, self::TYPE);
    }

}