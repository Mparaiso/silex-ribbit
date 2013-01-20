<?php

namespace Ribbit\Controllers;

use Ribbit\Entity\User;
use Ribbit\Entity\Ribbit;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ControllerProviderInterface;
use \Ribbit\Forms\RibbitCreateType;

class AdminController implements ControllerProviderInterface {

    /**
     * FR : affiche le profile utilisateur
     */
    function profile(Application $app, Request $req) {
        /**
         * @note @silex FR : créer un formulaire avec le conteneur 
         * $app["form.factory"]->create(new AbstractType());
         */
        $ribbitCreateForm = $app["form.factory"]->create(new RibbitCreateType());
        /* @var $ribbitCreateForm \Symfony\Component\Form\Form */
        $user = $app["security"]->getToken()->getUser();
        $ribbits = $app["ribbit_manager"]->findByUser($user);
        return $app['twig']->render("admin_profile.twig",
            array("ribbits" => $ribbits,"user" => $user)
            );
    }

    function users(Application $app,Request $req){
        $users = $app["user_manager"]->findAll();
        return $app["twig"]->render("admin_users.twig",array("users"=>$users));
    }

    function ribbits(Application $app,Request $req){
        $ribbits = $app["ribbit_manager"]->findAll();
        return $app["twig"]->render("admin_ribbits.twig",array("ribbits"=>$ribbits));
    }

    function publicRibbits(Application $app,Request $app){

    }

    /**
     * FR : l'authentification est gerée par le SecurityServiceProvider
     */
    function authenticate() {

    }

    /**
     * FR : la déconnexion est gérée par le SecurityServiceProvider
     */
    function logout(Application $app) {
        return $app->redirect($app["url_generator"]->generate("home"));
    }

    public function connect(Application $app) {
        $admin = $app["controllers_factory"];
        $admin->match("/profile", array($this, "profile"))->bind("admin_profile");
        $admin->match("/users",array($this,"users"))->bind("admin_users");
        $admin->match("/ribbits",array($this,"ribbits"))->bind("admin_ribbits");
        $admin->post("/authenticate", array($this, "authenticate"))->bind("users_authenticate");
        $admin->post("/logout", array($this, "logout"))->bind("users_logout");
        return $admin;
    }

}