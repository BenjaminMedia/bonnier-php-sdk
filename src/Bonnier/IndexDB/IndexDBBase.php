<?php
namespace Bonnier\IndexDB;

use Bonnier\ServiceItem;

abstract class IndexDBBase extends ServiceItem {
	protected $serviceUrl = 'https://indexdb.whitealbum.dk/api/%1$s/';
}