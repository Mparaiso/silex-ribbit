<?php

namespace Ribbit\DataAccessLayer;

use Silex\WebTestCase;

class DoctrineRoleProviderTest extends WebTestCase {

    /**
     * @var \Ribbit\DataAccessLayer\DoctrineRoleProvider $roleProvider
     */
    protected $roleProvider;

    /**
     * @covers Ribbit\DataAccessLayer\DoctrineRoleProvider::getByTitle
     * @todo   Implement testGetByTitle().
     */
    public function testGetByTitle() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    function setUp() {
        parent::setUp();
        $this->roleProvider = new DoctrineRoleProvider($this->app["em"]);
        createDB($this->app["em"], $this->app);
    }

    function tearDown() {
        parent::tearDown();
        dropDB($this->app["em"]);
    }

    function createApplication() {
        global $app;
        return $app;
    }

}
