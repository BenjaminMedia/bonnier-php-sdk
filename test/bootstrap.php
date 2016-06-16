<?php
namespace Bonnier\test;

use Dotenv\Dotenv;

include_once(dirname(__DIR__).'/vendor/autoload.php');

if (! getenv('IS_CIRCLE_CI')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
}