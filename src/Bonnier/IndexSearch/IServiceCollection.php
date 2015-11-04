<?php
namespace Bonnier\IndexSearch;

use Pecee\Http\HttpResponse;

interface IServiceCollection {

	public function setResponse(HttpResponse $response, $formattedResponse);

}