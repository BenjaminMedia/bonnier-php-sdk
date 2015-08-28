<?php
namespace Bonnier\IndexDB;

use Bonnier\HttpResponse;

class IndexServiceResult extends IndexSearchBase {

    protected $rows;

    public function setResponse(HttpResponse $response, $formattedResponse) {
        // Parse stuff here and add it to properties if nessesary.
    }

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