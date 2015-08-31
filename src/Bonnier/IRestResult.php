<?php

namespace Bonnier;

interface IRestResult {

	public function api($url = NULL, $method = self::METHOD_GET, array $data = NULL);

	public function execute();

	public function getService();

}