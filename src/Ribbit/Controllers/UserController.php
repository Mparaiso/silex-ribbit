<?php

namespace Ribbit\Controllers;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class UserController implements ControllerProviderInterface {

    function create(Application $app, Request $req) {
        if ($req->isMethod("POST")):
        // do post stuff
        else:
        // do get stuff
        endif;
        return $app["twig"]->render("users/create.twig");
    }

    public function connect(Application $app) {
        $app->match("new", array($this, "create"));
    }

}