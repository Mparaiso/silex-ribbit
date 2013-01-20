<?php

namespace Ribbit\Controllers;

use Silex\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Form\FormError;
use Ribbit\Entity\User;
use Ribbit\Forms\RegisterType;

class UserController implements ControllerProviderInterface {

    /**
     * FR : enregistre un nouvel utilisateur
     */
    function register(Application $app, Request $req) {
        $registrationForm = $app["form.factory"]->create(new RegisterType());
        /* @var $registrationForm \Symfony\Component\Form\Form */
        if ($req->getMethod() === "POST") {
            $registrationForm->bindRequest($app["request"]);
            if ($registrationForm->isValid()) {
                $datas = $registrationForm->getData();
                if ($app["user_manager"]->loadUserByUsername($datas["username"])) {
                    $registrationForm->get('username')
                            ->addError(new FormError("username already exists"));
                    $app["session"]->getFlashBag()
                            ->add("error", "username already exists");
                } else if ($app["user_manager"]->getByEmail($datas["email"])) {
                    $registrationForm->get("email")
                            ->addError(new FormError("email already exists"));
                    $app["session"]->getFlashBag()
                            ->add("error", "email already exists");
                } else if ($registrationForm->isValid()) {
                    //$role = $app["role_manager"]->getByTitle("ROLE_USER");
                    $user = new User();
                    $user->setUsername($datas["username"]);
                    $user->setEmail($datas["email"]);
                    //$user->addRole($role);
                    $user->setPassword($datas["password_repeated"]);
                    $user->setName($datas["name"]);
                    $app["user_manager"]->register($user);
                    if ($user->getId() > 0) {
                        $app["session"]->getFlashBag()->add("notice", "User {$user->getUsername()} created !,please login.");
                    }
                }
            }
        }
        return $app["twig"]->render("home.twig", array(
                    "registrationForm" => $registrationForm->createView()
                ));
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
            $app["logger"]->err($error);
            $session->setFlash("error", "User not found");
        }
        return $app->redirect($app["url_generator"]->generate($request->get("login_failed_forward", "home")));
    }

    public function connect(Application $app) {
        $user = $app["controllers_factory"];
        $user->match("/new", array($this, "register"))->bind("users_register");
        $user->match("/login", array($this, "login"))->bind("users_login");
        return $user;
    }

}