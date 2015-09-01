<?php

namespace Bonnier;

interface IRestResult {

	public function api($url = NULL, $method = RestBase::METHOD_GET, array $data = array());

	public function execute();

	public function getService();

}