<?php
/** @var DI\Container $container */

use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));
defined('CONFIG_PATH') or define('CONFIG_PATH', ROOT_PATH . DS . 'config');
defined('APP_PATH') or define('APP_PATH', ROOT_PATH . DS . 'src');
defined('APP_ENV') or define('APP_ENV', $_ENV['APP_ENV']);
defined('APP_IS_PRODUCTION') or define('APP_IS_PRODUCTION', APP_ENV === 'production');

require_once CONFIG_PATH . '/dic.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

// Register middleware
$middleware = require CONFIG_PATH . '/middleware.php';
$middleware($app);

// Register routes
$routes = require CONFIG_PATH . '/routes.php';
$routes($app);

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Add Error Middleware
$displayErrorDetails = $container->get('config')['displayErrorDetails'];
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);

$app->run();
