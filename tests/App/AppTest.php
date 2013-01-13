<?php
namespace App{
    class AppTest extends \Silex\WebTestCase{
        function createApplication(){
            global $app;
            return $app;
        }
        function testTest(){
            $this->assertInstanceOf("\Silex\Application",$this->app);
        }
    }
}