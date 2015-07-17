<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceItem;

abstract class IndexDBBase extends ServiceItem {
	protected $serviceUrl = 'https://staging-indexdb.whitealbum.dk/api/%1$s/';
}