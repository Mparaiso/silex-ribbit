<?php

namespace Ribbit\Controllers;

use Ribbit\Entity\User;
use Ribbit\Entity\Ribbit;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ControllerProviderInterface;
use \Ribbit\Forms\RibbitCreateType;
use Ribbit\Forms\FollowType;

class AdminController implements ControllerProviderInterface {

    /**
     * FR : affiche le profile utilisateur
     */
    function profile(Application $app, Request $req) {
        /**
         * @note @silex FR : créer un formulaire avec le conteneur 
         * $app["form.factory"]->create(new AbstractType());
         */
        $user = $app["security"]->getToken()->getUser();
        $ribbits = $app["ribbit_manager"]->findByUser($user);
        return $app['twig']->render("admin_profile.twig", array("ribbits" => $ribbits, "user" => $user)
        );
    }

    function users(Application $app, Request $req) {
        $users = $app["user_manager"]->findAll();
        return $app["twig"]->render("admin_users.twig", array("users" => $users));
    }

    function user(Application $app, Request $req, $id) {
        $user = $app["user_manager"]->getById($id);
        if ($user) {
            $ribbits = $app["ribbit_manager"]->findByUser($user);
            return $app["twig"]->render("admin_user.twig", array("user" => $user, "ribbits" => $ribbits));
        } else {
            $app["session"]->getFlashBag()->add("error", "User not found");
            return $app->redirect($app["url_generator"]->generate("home"));
        }
    }

    function ribbits(Application $app, Request $req) {
        $ribbits = $app["ribbit_manager"]->findAll();
        return $app["twig"]->render("admin_ribbits.twig", array("ribbits" => $ribbits));
    }

    function followeeRibbits(Application $app, Request $req) {
        $user = $app["security"]->getToken()->getUser();
        $ribbits = $app["ribbit_manager"]->findFolloweeRibbits($user);
        return $app["twig"]->render("admin_followee_ribbits.twig", array("ribbits" => $ribbits));
    }

    function follow(Application $app, Request $req, $id) {
        $form = $app["form.factory"]->create(new FollowType, array("user_id" => $id));
        if ($req->getMethod() === "POST") {
            /* @var $form \Symfony\Component\Form\Form */
            $form->bindRequest($req);
            if ($form->isValid()) {
                $data = $form->getData();
                $user = $app["security"]->getToken()->getUser();
                try {
                    $app["user_manager"]->follow($user, $data["user_id"]);
                    $app["session"]->getFlashBag()->add("notice", "Followee added");
                } catch (Exception $exc) {
                    $app["session"]->getFlashBag()->add("error", $exc->getMessage());
                }
            } else {
                $errors = $form->getErrorsAsString();
                $app["session"]->getFlashBag()->add("error", "Followee not added ! " . $errors);
            }
            return $app->redirect($req->headers->get("referer"));
        }
        return $app["twig"]->render("admin_follow.twig", array("id" => $id, "form" => $form->createView()));
    }

    function unfollow(Application $app, Request $req, $id) {
        $form = $app["form.factory"]->create(new FollowType, array("user_id" => $id));
        if ($req->getMethod() === "POST") {
            $form->bindRequest($req);
            if ($form->isValid()) {
                $data = $form->getData();
                $user = $app["security"]->getToken()->getUser();
                try {
                    $app["user_manager"]->unfollow($user, $data["user_id"]);
                    $app["session"]->getFlashBag()->add("notice", "Followee removed !");
                } catch (Exception $exc) {
                    $app["session"]->getFlashBag()->add("error", $exc->getMessage());
                }
            } else {
                $errors = $form->getErrorsAsString();
                $app["session"]->getFlashBag()->add("error", "Followee not removed ! " . $errors);
            }
            return $app->redirect($req->headers->get("referer"));
        }
        return $app["twig"]->render("admin_unfollow.twig", array("id" => $id, "form" => $form->createView()));
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
        $admin->match("/follow/{id}", array($this, "follow"))->bind("admin_follow");
        $admin->match("/unfollow/{id}", array($this, "unfollow"))->bind("admin_unfollow");
        $admin->match("/profile", array($this, "profile"))->bind("admin_profile");
        $admin->match("/users/{id}", array($this, "user"))->bind("admin_user");
        $admin->match("/users", array($this, "users"))->bind("admin_users");
        $admin->match("/ribbits", array($this, "ribbits"))->bind("admin_ribbits");
        $admin->match("/ribbits/followee",array($this,"followeeRibbits"))->bind("admin_followee_ribbits");
        $admin->post("/authenticate", array($this, "authenticate"))->bind("users_authenticate");
        $admin->post("/logout", array($this, "logout"))->bind("users_logout");
        return $admin;
    }

}