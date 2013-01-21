<?php

use Symfony\Component\ClassLoader\DebugClassLoader;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Doctrine\ORM\EntityManager;
putenv("RIBBIT_DRIVER=pdo_sqlite");
putenv("RIBBIT_ENVIRONMENT=development");

$loader = require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(-1);
DebugClassLoader::enable();
ErrorHandler::register();
if ('cli' !== php_sapi_name()) {
    ExceptionHandler::register();
}

$app = require __DIR__ . '/../src/app.php';


// EN : GENERATE DATABASE
// FR : GENERER LA BASE DE DONNEE

$app["em.opionts"]=array("driver"=>"pdo_sqlite","memory"=>true);

function createDB(EntityManager $em,  \Silex\Application $app){
    $em->getConfiguration()->setSQLLogger(new \Ribbit\Services\SQLLogger\MonologSQLLogger($app["logger"]));
    $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
    $classes = array(
        $em->getClassMetadata('Ribbit\Entity\User'),
        $em->getClassMetadata('Ribbit\Entity\Role'),
        $em->getClassMetadata("Ribbit\Entity\Ribbit")
    );
    $tool->createSchema($classes);
}

function dropDB(EntityManager $em){
    $tool = new Doctrine\ORM\Tools\SchemaTool($em);
    $tool->dropDatabase();
}