<?php
namespace Bonnier;

use Bonnier\Service\ServiceBase;
use Bonnier\Service\ServiceResult;

class ServiceApplication extends ServiceBase {

    const TYPE = 'application';

    public function __construct($secret) {
        parent::__construct($secret, self::TYPE);
    }

    public function get() {
        $result = new ServiceResult($this->secret, $this->type);
        return $result->api();
    }

    public function getById($id) {
        return $this->api($id);
    }

}