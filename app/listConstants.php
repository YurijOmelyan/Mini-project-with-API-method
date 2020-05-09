<?php

define('ROOT', str_replace('/app', '', __DIR__));
define('ROUTER', ROOT . '/app/Router.php');
define('FORM_LOGIN', ROOT . '/app/views/authorizationForm.php');
define('FORM_TABLE', ROOT . '/app/views/currencyQuotesForm.php');

define('PATH_CONFIG', ROOT . '/app/config/');
define('PATH_CONTROLLER', ROOT . '/app/controllers/');
define('PATH_MODEL', ROOT . '/app/models/');

define('ROOTS', PATH_CONFIG . 'roots.php');
define('DB_SETTING', PATH_CONFIG . 'dbSettings.php');
define('DATA_BASE_CONNECTION', ROOT . '/app/DatabaseConnection.php');