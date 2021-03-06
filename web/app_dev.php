<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

/*
 * Dev environment.
 *
 * To develop on Jedisjeux in Docker set the JDJ_APP_DEV_PERMITTED to a non zero value.
 * e.g. in etc/docker/php/default.conf:
 *
 *   env[JDJ_APP_DEV_PERMITTED] = 1
 */
if (!getenv("APP_NAME_APP_DEV_PERMITTED")
    && !getenv("JDJ_APP_DEV_PERMITTED")
    && (
        isset($_SERVER['HTTP_CLIENT_IP'])
        || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        || !(in_array(@$_SERVER['REMOTE_ADDR'], array('10.0.0.1', '127.0.0.1', 'fe80::1', '::1')) || php_sapi_name() === 'cli-server')
    )
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check ' . basename(__FILE__) . ' for more information.');
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
