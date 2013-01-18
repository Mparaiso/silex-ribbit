<?php

namespace Ribbit\Controllers;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Ribbit\Forms\RegisterType;

class IndexController implements ControllerProviderInterface {

    function index(Application $app) {
        $registrationForm = $app['form.factory']->create(new RegisterType());
        if($app["request"]->getMethod()==="POST"){
            $registrationForm->bindRequest($app["request"]);
        }
        return $app['twig']->render('home.twig', array(
         "registrationForm" => $registrationForm->createView() ,
         "login_failed_forward"=>"home"));
    }

    public function connect(Application $app) {
        $index = $app["controllers_factory"];
        $index->match("/", array($this, "index"))->bind("home");
        return $index;
    }

}