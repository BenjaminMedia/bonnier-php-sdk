<?php
namespace Bonnier\test;

use Dotenv\Dotenv;

include_once(dirname(__DIR__).'/vendor/autoload.php');

$dotenv = new Dotenv(__DIR__);
$dotenv->load();