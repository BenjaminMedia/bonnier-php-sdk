<?php
namespace Bonnier;

class RestItem implements IRestResult {

    protected $row;
    protected $service;

    public function __construct(RestBase $service) {
        $this->row = new \stdClass();
        $this->service = $service;
    }

    public function setRow(\stdClass $row) {
        $this->row = $row;
    }

    public function __set($name, $value = NULL) {
        $this->row->$name = $value;
    }

    public function __get($name) {
        return (isset($this->row->$name)) ? $this->row->$name : NULL;
    }

    public function getRow() {
        return $this->row;
    }

    /**
     * Get single item by id
     *
     * @param string $id
     * @throws \Bonnier\ServiceException
     * @return static
     */
    public function getById($id) {
        $this->row = $this->api($id)->getRow();
        return $this;
    }

    /**
     * Delete item by id
     *
     * @param string $id
     * @throws \Bonnier\ServiceException
     * @return static
     */
    public function delete($id) {
        return $this->api($id, RestBase::METHOD_DELETE);
    }

    /**
     * Update item
     *
     * @throws \Bonnier\ServiceException
     * @return static
     */
    public function update() {
        $this->row = $this->api($this->id, RestBase::METHOD_PUT, (array)$this->row)->getRow();
        return $this;
    }

    /**
     * Save item
     *
     * @throws \Bonnier\ServiceException
     * @return static
     */
    public function save() {
        $this->row = $this->api(NULL, RestBase::METHOD_POST, (array)$this->row)->getRow();
        return $this;
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
        return $this->service->api($url, $method, $data);
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