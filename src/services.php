<?php

/**
 * custom config for silex app , just to keep the app.php
 * file clean
 */
use Silex\Provider\SessionServiceProvider;

/* @var $app Silex\Application */
$app->register(new SessionServiceProvider(), array(
    "session.storage_options" => array(
        "httponly" => true,
    ),
        )
);