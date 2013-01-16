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
use Ribbit\BusinessLogicLayer\UserManager;
use Ribbit\DataAccessLayer\DoctrineUserProvider;
use Ribbit\Services\SQLLogger\MonologSQLLogger;

/* @var $app Silex\Application */
$app["current_time"] = function() {
    return date('Y-m-d H:i:s', time());
};
$app["user_provider"] = $app->share(function(Application $app) {
    return new DoctrineUserProvider($app["em"]);
}
);
# EN : custom services
$app["user_manager"] = $app->share(function(Application $app) {
    return new UserManager($app["user_provider"], $app);
}
);

$app["em"] = $app->share(
    function(Application $app) {
        
        $s = DIRECTORY_SEPARATOR;
        
        $app["em.is_dev_mode"] = $app['debug'];
        $app["em.metadata_folders"] = array(__DIR__ . $s . "Ribbit" . $s . "Entity" . $s);
        $app["em.proxy_dir"] = dirname(__DIR__) . $s . "cache";

        /** @var $app["em.config"] \Doctrine\ORM\Configuration * */
        $app["em.config"] = Setup::createAnnotationMetadataConfiguration(
            $app["em.metadata_folders"]
            , $app["em.is_dev_mode"]
            , $app["em.proxy_dir"]
            );

        if($app["debug"]==true){
            $app["em.config"]->setSQLLogger(new MonologSQLLogger($app["logger"]));
        }
        
        $app["em.options"] = array(
            "host" => getenv("RIBBIT_HOST"),
            "dbname" => getenv("RIBBIT_DATABASE"),
            "user" => getenv("RIBBIT_USERNAME"),
            "password" => getenv("RIBBIT_PASSWORD"),
            "driver" => getenv("RIBBIT_DRIVER"),
            "memory"=>true
            );

        $em =  EntityManager::create($app["em.options"], $app["em.config"]);
        return $em;
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

// EN : logger is already declared in config/dev.php file