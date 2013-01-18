<?php

namespace Ribbit\Controllers;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Ribbit\Entity\User;
use Ribbit\Forms\RegisterType;

class UserController implements ControllerProviderInterface {

    function register(Application $app, Request $req) {
        $formRegister = $app["form"]->create(new RegisterType());
        if ($req->getMethod()==="POST"){
            $formRegister->bindRequest($app["request"]);
            if($formRegister->isValid()){
                if($app["user_manager"]->loadByUsername($formRegister->get("username"))){
                    $formRegister->addError("username already exists");
                }else if($app["user_manager"]->findByEmail($formRegister->get("email"))){
                    $formRegister->addError("email already exists");
                }else{
                    // form is valid and username + email are unique.
                }
            }
        }
        return $app["twig"]->render("users/create.twig");
    }

    /**
     * FR : l'app se charge d'authentifier l'utilisateur , ce controleur ne
     * fait qu'afficher le formulaire et les erreurs éventuelles
     * @param \Silex\Application $app
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @return type
     */
    function login(Application $app, Request $request) {
        $session = $app["session"];
        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        if ($error) {
            $session->setFlash("error", "User not found");
        }
        return $app["twig"]->render(
                        "home.twig", array(
                    "last_username" => $session->get(
                            SecurityContext::LAST_USERNAME
                    ),
                        )
        );
    }

    public function connect(Application $app) {
        $user = $app["controllers_factory"];
        $user->match("/new", array($this, "create"))->bind("users_register");
        $user->match("/login", array($this, "login"))->bind("users_login");
        return $user;
    }

}
