<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Ribbit\BusinessLogicLayer\UserManager;
use Ribbit\DataAccessLayer\DoctrineUserProvider;
use Ribbit\Services\Provider\DoctrineORMServiceProvider;
use Ribbit\Controllers\UserController;
use Ribbit\Controllers\IndexController;
use Ribbit\Controllers\AdminController;
use Ribbit\Controllers\RibbitController;
use Ribbit\Services\SQLLogger\MonologSQLLogger;
use Ribbit\DataAccessLayer\DoctrineRoleProvider;
use Ribbit\BusinessLogicLayer\RoleManager;
use Ribbit\DataAccessLayer\DoctrineRibbitProvider;
use Ribbit\BusinessLogicLayer\RibbitManager;

$app = new Application();

$app["debug"] = getenv("RIBBIT_ENVIRONMENT") == "development" ? true : false ;
// redirect user if logged in
$app["mustBeAnonymous"] = $app->protect(function()use($app){
    if($app["security"]->isGranted('ROLE_USER')){
        return $app->redirect($app["url_generator"]->generate("admin_profile"));
    }
}
);
$app["void"] = $app->protect(function() {
});
$app["current_time"] = function() {
    return date('Y-m-d H:i:s', time());
};
$app["role_provider"] = $app->share(function(Application $app) {
    return new DoctrineRoleProvider($app["em"]);
});
$app["role_manager"] = $app->share(function(Application $app) {
    return new RoleManager($app["role_provider"]);
});
$app["user_provider"] = $app->share(function(Application $app) {
    return new DoctrineUserProvider($app["em"]);
}
);
$app["user_manager"] = $app->share(function(Application $app) {
    $userManager = new UserManager($app["user_provider"], $app["security.encoder_factory"]);
    return $userManager;
}
);
$app["ribbit_provider"] = $app->share(function(Application $app) {
    return new DoctrineRibbitProvider($app["em"]);
}
);
$app["ribbit_manager"] = $app->share(function(Application $app) {
    return new RibbitManager($app["ribbit_provider"]);
}
);
// FR : Enrigistrement des services providers
// EN : Service providers registration
$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../temp/'.date("Y:m:d").'.log',
    ));
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TranslationServiceProvider(), array(
    "locale_fallback" => "en",
    ));
$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/templates'),
    'twig.options' => array('cache' => __DIR__ . '/../temp/twig'),
    ));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
                    // add custom globals, filters, tags, ...

    return $twig;
}));
$s = DIRECTORY_SEPARATOR;
$app->register(new DoctrineORMServiceProvider(), array(
    "em.options" => array(
        "host" => getenv("RIBBIT_HOST"),
        "dbname" => getenv("RIBBIT_DATABASE"),
        "user" => getenv("RIBBIT_USERNAME"),
        "password" => getenv("RIBBIT_PASSWORD"),
        "driver" => getenv("RIBBIT_DRIVER"),
        "memory" => true
        ),
    "em.logger" => function($app) {
        return new MonologSQLLogger($app["logger"]);
    },
    "em.metadata" => array(
        "type" => "annotation",
        "path" => array(__DIR__ . $s . "Ribbit" . $s . "Entity" . $s),
        ),
    "em.proxy_dir" => dirname(__DIR__) . $s . "cache",
    "em.is_dev_mode" => $app["debug"]
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
                "login_path" => "/users/login",
                "check_path" => "/admin/authenticate",
                "default_target_path" => "/admin/profile",
                ),
            "logout" => array(
                "logout_path" => "/admin/logout",
                "target" => "/",
                "invalidate_session" => true,
                "delete_cookies" => array()
                ),
            "users" => function(Application $app) {
                return $app["user_manager"];
            }
            )
        ),
    "security.role_hierarhy" => array(
        "ROLE_USER" => array(),
        ),
    "security.access_rules" => array(
        array("^/admin", "ROLE_USER"),
        )
    ));

# CONTROLLERS
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});

$app["user_controller"]=$app->share(function($app){
return new UserController;
});

$app->mount("/users", $app["user_controller"]);
$app->mount("/admin/ribbit",new RibbitController);
$app->mount("/admin", new AdminController);
$app->mount("/", new IndexController);

### ENDCUSTOMCODE
return $app;
