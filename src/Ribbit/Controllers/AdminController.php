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
    function userProfile(Application $app, Request $req) {
        /**
         * @note @silex FR : créer un formulaire avec le conteneur 
         * $app["form.factory"]->create(new AbstractType());
         */
        $ribbitCreateForm = $app["form.factory"]->create(new RibbitCreateType());
        /* @var $ribbitCreateForm \Symfony\Component\Form\Form */
        $user = $app["security"]->getToken()->getUser();
        $ribbits = $app["ribbit_manager"]->findByUser($user);
        return $app['twig']->render("admin/users/profile.twig",
            array("ribbits" => $ribbits,"user" => $user)
            );
    }

    function publicProfiles(Application $app,Request $req){
        $users = $app["user_manager"]->findAll();
        return $app["twig"]->render("admin.twig",array("users"=>$users));
    }

    function followeeRibbits(Application $app,Request $app){

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
    function logout() {

    }

    public function connect(Application $app) {
        $admin = $app["controllers_factory"];
        $admin->match("/profile", array($this, "userProfile"))->bind("users_profile");
        $admin->match("/users",array($this,"publicProfiles"))->bind("users_public");
        $admin->post("/authenticate", array($this, "authenticate"))->bind("users_authenticate");
        $admin->post("/logout", array($this, "logout"))->bind("users_logout");
        return $admin;
    }

}