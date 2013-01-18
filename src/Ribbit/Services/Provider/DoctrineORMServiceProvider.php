<?php

namespace Ribbit\Services\Provider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineORMServiceProvider implements ServiceProviderInterface {

    public function boot(Application $app) {

    }

    public function register(Application $app) {
        $app["em"] = $app->share(function($app) {
            if (!is_array($app["em.metadata"]["path"])) {
                throw new Exception("\$app['em.metadata']['path'] must be an array of paths");
            }
            if ($app["em.metadata"]["type"] === "annotation") {
                $app["em.config"] = function($app) {
                    return Setup::createAnnotationMetadataConfiguration(
                        $app["em.metadata"]["path"]
                        , $app["em.is_dev_mode"]
                        );
                };
            } elseif ($app["em.metadata"]["type"] === "xml") {
                $app["em.config"] = function($app) {
                    return Setup::createXMLMetadataConfiguration(
                        $app["em.metadata"]["path"], $app["em.is_dev_mode"]
                        );
                };
            } elseif ($app["em.metadata"]["type"] === "yaml") {
                $app["em.config"] = function($app) {
                    return Setup::createYAMLMetadataConfiguration(
                        $app["em.metadata"]["path"], $app["em.is_dev_mode"]);
                };
            }
            if ($app["debug"] == true) {
                $app["em.is_dev_mode"] = true;
            }
            if ($app['em.logger']) {
                $app["em.config"]->setSQLLogger($app['em.logger']);
            }
            if ($app["em.proxy_dir"]) {
                $app["em.config"]->setProxyDir($app["em.proxy_dir"]);
            }
            return EntityManager::create($app["em.options"], $app["em.config"]);
        }
        );
}

}