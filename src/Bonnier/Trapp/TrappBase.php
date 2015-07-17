<?php
namespace Bonnier\Trapp;

use Bonnier\ServiceItem;

abstract class TrappBase extends ServiceItem {
	protected $serviceUrl = 'http://local.trapp.dk/api/v1/%1$s/';
}