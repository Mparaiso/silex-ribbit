<?php

namespace Ribbit\Controllers;

use Silex\ControllerProviderInterface;
use Ribbit\Entity\Ribbit;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Ribbit\Forms\RibbitCreateType;

class RibbitController implements ControllerProviderInterface {

    function create(Application $app, Request $req) {
        $form = $app["form.factory"]->create(new RibbitCreateType);
        if ($req->getMethod() === "POST") {
            $form->bindRequest($req);
            if ($form->isValid()) {
                $data = $form->getData();
                $user = $app["security"]->getToken()->getUser();
                $ribbit = new Ribbit;
                $ribbit->setRibbit($data["ribbit"]);
                $ribbit->setUser($user);
                try {
                    $app["ribbit_manager"]->create($ribbit);
                    $app["session"]->getFlashBag()->add("notice", "Ribbit saved.");
                } catch (Exception $exc) {
                    $app["logger"]->err($exc->getMessage());
                    $app["session"]->getFlashBag()->add("error", "Error saving Ribbit.");
                }
            } else {
                $app["session"]->getFlashBag()->add("error", "Error saving Ribbit, form is not valid.");
            }
            return $app->redirect($req->headers->get("referer"));
        }
        return $app["twig"]->render("form_ribbit_create.twig", 
                array("form" => $form->createView(),
                    "action"=>$app["url_generator"]->generate("ribbit_create")));
    }

    function connect(Application $app) {
        $controller = $app["controllers_factory"];
        $controller->match("/create", array($this, "create"))->bind("ribbit_create");
        return $controller;
    }

}