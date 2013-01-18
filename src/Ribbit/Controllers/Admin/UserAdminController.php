<?php

namespace Ribbit\Controllers\Admin;

use Ribbit\Entity\User;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ControllerProviderInterface ;

class UserAdminController implements ControllerProviderInterface {

    /**
     * FR : affiche le profile utilisateur
     */
    function profile(Application $app,Request $req){
        $user = $app["security"]->getToken()->getUser();
        return $app['twig']->render("admin/users/profile.twig",array("user"=>$user));
    }
    /**
     * FR : l'authentification est gerée par le SecurityServiceProvider
     */
    function authenticate(){}

    /**
     * FR : la déconnexion est gérée par le SecurityServiceProvider
     */
    function logout(){}
    
    public function connect(Application $app) {
        $userAdmin = $app["controllers_factory"];
        $userAdmin->match("/profile", array($this, "profile"));
        $userAdmin->post("/authenticate",array($this,"authenticate"))->bind("users_authenticate");
        $userAdmin->post("/logout",array($this,"logout"))->bind("users_logout");
        return $userAdmin;
    }

}