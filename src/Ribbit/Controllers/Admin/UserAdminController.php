<?php

namespace Ribbit\Controllers\Admin;

use Ribbit\Entity\User;
use Ribbit\Entity\Ribbit;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ControllerProviderInterface;
use \Ribbit\Forms\RibbitCreateType;

class UserAdminController implements ControllerProviderInterface {

    /**
     * FR : affiche le profile utilisateur
     */
    function profile(Application $app, Request $req) {
        /**
         * @note @silex FR : créer un formulaire avec le conteneur 
         * $app["form.factory"]->create(new AbstractType());
         */
        $ribbitCreateForm = $app["form.factory"]->create(new RibbitCreateType());
        $user = $app["security"]->getToken()->getUser();
        if ($req->getMethod() === "POST") {
            $ribbitCreateForm->bindRequest($req);
            if ($ribbitCreateForm->isValid()) {
                $datas = $ribbitCreateForm->getData();
                $ribbit = new Ribbit();
                $ribbit->setUser($user);
                $ribbit->setRibbit($datas["ribbit"]);
                try {
                    $app["ribbit_manager"]->create($ribbit);
                    /**
                     *  @note @silex FR : obtenir le flashbag  $app["session"]->getFlashBag();
                     */
                    $app["session"]->getFlashBag()->add("notice", "Ribbit successfully saved.");
                } catch (\Exception $exc) {
                    $app["logger"]->err($exc->getMessage());
                    $app["session"]->getFlashBag()->add("error", "Error saving ribbit");
                }
            }
        }
        $ribbits = $app["ribbit_manager"]->findByUser($user);
        return $app['twig']->render("admin/users/profile.twig", array(
                    "ribbits" => $ribbits,
                    "user" => $user,
                    "ribbitCreateForm" => $ribbitCreateForm->createView()));
    }

    /**
     * FR : l'authentification est gerée par le SecurityServiceProvider
     */
    function authenticate() {
        
    }

    /**
     * FR : la déconnexion est gérée par le SecurityServiceProvider
     */
    function logout() {
        
    }

    public function connect(Application $app) {
        $userAdmin = $app["controllers_factory"];
        $userAdmin->match("/profile", array($this, "profile"));
        $userAdmin->post("/authenticate", array($this, "authenticate"))->bind("users_authenticate");
        $userAdmin->post("/logout", array($this, "logout"))->bind("users_logout");
        return $userAdmin;
    }

}