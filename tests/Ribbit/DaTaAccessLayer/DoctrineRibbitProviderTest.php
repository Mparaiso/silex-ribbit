<?php

namespace Ribbit\DataAccessLayer;

use Ribbit\DataAccessLayer\DoctrineRibbitProvider;
use Ribbit\Entity\User;

class DoctrineRibbitProvider extends \Silex\WebTestCase{
    
    public function createApplication() {
        global $app;
        return $app;
    }    

    function setUp(){
        parent::setUp();
        $this->doctrineRibbitProvider = new DoctrineRibbitProvider($app["em"]);
    }
    
    function testGetByUser(){
        $user = new User();
        $ribbits = $this->doctrineRibbitProvider->getByUser($user);
        $this->assertCount(4,$ribbits);
    }
    
}