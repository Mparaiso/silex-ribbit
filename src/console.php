<?php

// @note @php @symfony FR : créer une application console
use Symfony\Component\Console\Application;


/** @var $app Silex\Application * */
$console = new Application('My Silex Application', 'n/a');

require_once("commands.php");

return $console;
