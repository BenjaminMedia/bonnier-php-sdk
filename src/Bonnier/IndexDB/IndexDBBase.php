<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceItem;

abstract class IndexDBBase extends ServiceItem {
	protected $serviceUrl = 'https://staging-indexdb.whitealbum.dk/api/v1/%1$s/';
}