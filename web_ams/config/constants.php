<?php
define('BASE_PATH', realpath(__DIR__ . '/..'));

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
define('BASE_URL', 'https://ams-upt.sytes.net');
define('APP_NAME', 'Sistema de Mentoría Académica');
define('DEBUG', true);
