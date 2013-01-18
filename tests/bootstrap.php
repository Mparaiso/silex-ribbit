<?php

putenv("RIBBIT_DRIVER=pdo_sqlite");
putenv("RIBBIT_ENVIRONMENT=development");

use Symfony\Component\ClassLoader\DebugClassLoader;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;

$loader = require_once __DIR__ . '/../vendor/autoload.php';


ini_set('display_errors', 1);
error_reporting(-1);
DebugClassLoader::enable();
ErrorHandler::register();
if ('cli' !== php_sapi_name()) {
    ExceptionHandler::register();
}

$app = require __DIR__ . '/../src/app.php';
require __DIR__ . '/../config/dev.php';


// EN : GENERATE DATABASE
// FR : GENERER LA BASE DE DONNEE
$em = $app["em"];
$tool = new \Doctrine\ORM\Tools\SchemaTool($em);
$classes = array(
    $em->getClassMetadata('Ribbit\Entity\User'),
    $em->getClassMetadata('Ribbit\Entity\Role'),
);
$tool->createSchema($classes);


