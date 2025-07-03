<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__ . '/..'));
}
if (!defined('PHPUNIT_RUNNING')) {
    define('PHPUNIT_RUNNING', true);
}

require_once BASE_PATH . '/config/constants.php'; // solo si lo usas
require_once BASE_PATH . '/models/Usuario.php';
require_once BASE_PATH . '/controllers/AuthController.php';