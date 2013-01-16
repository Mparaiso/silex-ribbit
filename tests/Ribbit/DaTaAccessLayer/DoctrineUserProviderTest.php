<?php

namespace Ribbit\DataAccessLayer;

use Ribbit\Entity\User;

class DoctrineUserProviderTest extends \Silex\WebTestCase{

    /**
     * @var Ribbit\DataAccessLayer\IUserProvider $userProvider
     */
    protected $userProvider;

    function setUp(){
        parent::setUp();
        $this->userProvider = new DoctrineUserProvider($this->app["em"]);
    }

    function tearDown(){
        parent::tearDown();
    }
    function createApplication(){
        global $app;
        return $app;
    }
    function testConstruct(){
        $this->assertNotNull($this->userProvider);
    }
    function testCreate(){
        $user = new User();
        $user->setName("Clark Kent");
        $user->setUsername("Superman");
        $user->setPasswordDigest("password");
        $user->setEmail("superman@justiceleague.com");
        $user->setSalt("salt");
        $user->setCreatedAt(new \DateTime("now"));
        $user->setUpdatedAt(new \DateTime("now"));
        $newUser = $this->userProvider->create($user);
        $this->assertEquals(1,$newUser->getId());
        return $user;
    }

    /**
     * @depends testCreate
     */
    function testGetGetByUsername(User $user){
        $result = $this->userProvider->getByUsername($user->getUsername());
        $this->assertCount(1,$result);
        $this->assertEquals($user,$result[0]);
        $this->assertEquals($user->getUsername(),$result[0]->getUsername());
    }
}
