<?php
namespace Bonnier;

use Bonnier\Service\ServiceBase;

class ServiceApplications extends ServiceBase {

    const TYPE = 'applications';

    public function __construct($secret) {
        parent::__construct($secret, self::TYPE);
    }

    public function get() {
        return $this->api();
    }

    public function getById($id) {
        return $this->api($id);
    }

}