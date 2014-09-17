<?php
/**
 * Bootstrap File for including necessary config files and autoloader
 *
 * @author Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

define('APP_PATH', __DIR__);
include_once __DIR__.'/../src/Loader.php';

\Kernel\Router\Router::getInstance()->load(include 'routes.php');
Application::loadConfig(include 'config.php');

