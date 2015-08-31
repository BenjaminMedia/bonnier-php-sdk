<?php
namespace Bonnier;

class RestCollection extends RestResultAdapter {

    protected $rows;

    public function __construct(RestBase $service) {
        $this->service = $service;
    }

    public function getRows() {
        return $this->rows;
    }

    public function setRows($rows) {
        $this->rows = $rows;
    }
}