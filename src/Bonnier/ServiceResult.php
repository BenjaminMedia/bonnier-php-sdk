<?php
namespace Bonnier;
class ServiceResult extends RESTBase {

    protected $rows;

    public function execute() {
        return $this->api();
    }

    public function getRows() {
        return $this->rows;
    }

    public function setRows($rows) {
        $this->rows = $rows;
    }
}