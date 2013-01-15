<?php

/**
 * @author M.PARAISO
 * custom config for silex app , just to keep the app.php
 * file clean
 */
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Application;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Ribbit\BLL\UserManager;
use Ribbit\DAL\UserProvider;

/* @var $app Silex\Application */
$app["user_provider"] = $app->share(function(Application $app) {
            return new UserProvider($app["db"]);
        }
);
# EN : custom services
$app["user_manager"] = $app->share(function(Application $app) {
            return new UserManager($app["user_provider"], $app);
        }
);

$app["em"] = $app->share(
        function(Application $app) {

            $app["em.is_dev_mode"] = true;
            $app["em.metadata_folder"] = __DIR__ . "/Ribbit/Entities";
            $app["em.config"] = Setup::createAnnotationMetadataConfiguration(
                            array(
                                $app["em.metadata_folder"]
                                , $app["em.is_dev_mode"]
                            )
            );

            $app["em.options"] = array(
                "host" => getenv("RIBBIT_HOST"),
                "database" => getenv("RIBBIT_DATABASE"),
                "username" => getenv("RIBBIT_USERNAME"),
                "password" => getenv("RIBBIT_PASSWORD"),
                "driver" => getenv("RIBBIT_DRIVER"),
            );

            return EntityManager::create($app["em.options"], $app["em.config"]);
        }
);

/* @var $app Silex\Application */
$app->register(new DoctrineServiceProvider(), array(
    "db.options" => array(
        "host" => getenv("RIBBIT_HOST"),
        "database" => getenv("RIBBIT_DATABASE"),
        "username" => getenv("RIBBIT_USERNAME"),
        "password" => getenv("RIBBIT_PASSWORD"),
        "driver" => getenv("RIBBIT_DRIVER"),
    )
));

$app->register(new SessionServiceProvider(), array(
    "session.storage_options" => array(
        "httponly" => true,
    ),
        )
);
// EN : all the app must be behind the firewall allowing anonymous users, 
// secure urls are listed in security.access_rules array
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        "protected" => array(
            "anonymous" => array(),
            'pattern' => '^/',
            "form" => array(
                "login_path" => "/login",
                "check_path" => "/account/login_check",
            ),
            "logout" => array(
                "logout_path" => "/account/logout",
                "target" => "/",
                "invalidate_session" => true,
                "delete_cookies" => array()
            ),
            "users" => function(Application $app) {
                return $app["auth_manager"];
            }
        )
    ),
    "security.role_hierarhy" => array(
        "ROLE_ADMIN" => array("ROLE_MODERATOR"), // super admin
        "ROLE_MODERATOR" => array("ROLE_USER"), //
        "ROLE_USER" => array(),
    ),
    "security.acess_rules" => array(
        array("^/account", "ROLE_USER"),
        array("^/admin", "ROLE_MODERATOR"),
        array("^/superadmin", "ROLE_ADMIN"),
    )
));


