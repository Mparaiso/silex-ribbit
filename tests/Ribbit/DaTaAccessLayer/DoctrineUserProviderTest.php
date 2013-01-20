<?php

namespace Ribbit\DataAccessLayer;

use Ribbit\Entity\User;

class DoctrineUserProviderTest extends \Silex\WebTestCase {

    /**
     * @var Ribbit\DataAccessLayer\IUserProvider $userProvider
     */
    protected $userProvider;

    function setUp() {
        parent::setUp();
        $this->userProvider = new DoctrineUserProvider($this->app["em"]);
        createDB($this->app["em"], $this->app);
        $this->app["logger"]->info("SETUP");
    }

    function tearDown() {
        parent::tearDown();
        dropDB($this->app["em"]);
    }

    function createApplication() {
        global $app;
        return $app;
    }

    function testConstruct() {
        $this->assertNotNull($this->userProvider);
    }
    /**
     * @dataProvider userProvider
     * @param User[] $users
     * @param integer $count
     * @return \Ribbit\Entity\User
     */
    function testCreate($users,$count) {
        $newUser = $this->userProvider->create($users[0]);
        $this->assertEquals(1, $newUser->getId());
    }

    /**
     * @dataProvider userProvider
     */
    function testGetGetByUsername($users, $count) {
        $this->userProvider->create($users[0]);
        $result = $this->userProvider->getByUsername($users[0]->getUsername());
        $this->assertNotNull($result);
        $this->assertEquals("Superman", $result->getUsername());
    }

    function userProvider() {
        $superman = new User();
        $superman->setName("Clark Kent");
        $superman->setUsername("Superman");
        $superman->setPassword("password");
        $superman->setEmail("superman@justiceleague.com");
        $superman->setSalt("salt");
        $superman->setCreatedAt(new \DateTime("now"));
        $superman->setUpdatedAt(new \DateTime("now"));
        $greenArrow = new User();
        $greenArrow->setUsername("GreenArrow");
        $greenArrow->setEmail("greenarrow@justiceleague.com");
        $greenArrow->setName("Green Arrow");
        $greenArrow->setPassword("password");
        $greenArrow->setCreatedAt(new \DateTime("now"));
        $greenArrow->setUpdatedAt(new \DateTime("now"));
        $wonderWoman = new User();
        $wonderWoman->setUsername("WonderWoman");
        $wonderWoman->setEmail("wonderwoman@justiceleague.com");
        $wonderWoman->setName("Wonder Woman");
        $wonderWoman->setPassword("password");
        $wonderWoman->setCreatedAt(new \DateTime("now"));
        $wonderWoman->setUpdatedAt(new \DateTime("now"));
        $flash = new User();
        $flash->setUsername("Flash");
        $flash->setEmail("flash@justiceleague.com");
        $flash->setName("flash");
        $flash->setPassword("password");
        $flash->setCreatedAt(new \DateTime());
        $flash->setUpdatedAt(new \DateTime);
        return array(
            array(array($superman, $greenArrow, $wonderWoman, $flash), 4)
        );
    }

    /**
     * @dataProvider userProvider
     */
    function testCount($users, $count) {
        $this->assertCount($count, $users);
        foreach ($users as $user):
            $this->userProvider->create($user);
        endforeach;
        $this->assertCount($count, $this->userProvider->get());
    }

}
