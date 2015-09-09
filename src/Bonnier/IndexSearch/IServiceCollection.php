<?php
namespace Bonnier\IndexSearch;

use Bonnier\HttpResponse;

interface IServiceCollection {

	public function setResponse(HttpResponse $response, $formattedResponse);

}