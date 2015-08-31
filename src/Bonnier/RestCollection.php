<?php
namespace Bonnier;

class RestCollection implements IRestResult {

    /**
     * @var RestBase
     */
    protected $service;
    protected $rows;

    public function __construct(RestBase $service) {
        $this->service = $service;
    }

    public function getRow($index) {
        return (isset($this->rows[$index])) ? $this->rows[$index] : NULL;
    }

    public function getRows() {
        return $this->rows;
    }

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /**
     * Execute api call
     *
     * @param null $url
     * @param string $method
     * @param array|null $data
     *
     * @throws ServiceException
     * @return self
     */
    public function api($url = null, $method = RestBase::METHOD_GET, array $data = null) {
        return $this->getService()->api($url, $method, $data);
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

    public function getService() {
        return $this->service;
    }
}