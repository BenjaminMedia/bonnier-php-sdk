<?php
namespace Bonnier\IndexSearch\REST;

use Bonnier\HttpResponse;
use Bonnier\IndexSearch\ServiceBase;

class RESTCollection extends ServiceBase {

    protected $rows;

    public function setResponse(HttpResponse $response, $formattedResponse) {
        // Parse stuff here and add it to properties if nessesary.
    }

    /**
     * Execute api call.
     *
     * Alias for $this->api();
     *
     * @return self
     */
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