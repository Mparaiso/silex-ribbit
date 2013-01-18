<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Symfony\Component\HttpFoundation\Response;

use Ribbit\BusinessLogicLayer\UserManager;
use Ribbit\DataAccessLayer\DoctrineUserProvider;
use Ribbit\Services\Provider\DoctrineORMServiceProvider;
use Ribbit\Controllers\UserController;
use Ribbit\Controllers\IndexController;
use Ribbit\Services\SQLLogger\MonologSQLLogger;

$app = new Application();
### BEGINCUSTOMCODE 
$app["debug"]=true;
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
// FR : Enrigistrement des services providers
// EN : Service providers registration
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TranslationServiceProvider(),array(
    "locale_fallback"=>"en",
));
$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/templates'),
    'twig.options' => array('cache' => __DIR__ . '/../cache'),
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
    "em.logger" => $app->share(function($app) {
                return new MonologSQLLogger($app["logger"]);
            }),
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
                "check_path" => "/admin/users/authenticate",
            ),
            "logout" => array(
                "logout_path" => "/admin/users/logout",
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
    "security.acess_rules" => array(
        array("^/admin/", "ROLE_USER"),
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

$app->mount("/users", new UserController());
$app->post("/admin/users/authenticate",function(){})->bind("login_check");
$app->mount("/",new IndexController());

### ENDCUSTOMCODE
return $app;
