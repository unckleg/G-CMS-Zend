<?php

define('APPLICATION_ENV', 'development');

define('DS', DIRECTORY_SEPARATOR);

define('ZEND_LIBRARY_PATH', realpath('../library'));

define('APPLICATION_PATH', realpath('../application' ));

define('APP_LIBRARY_PATH', APPLICATION_PATH . '../library/UltimateCMS');

define('APP_PUBLIC', realpath(''));


$paths = array( ZEND_LIBRARY_PATH, APP_LIBRARY_PATH, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $paths));

require_once 'Zend/Application.php';

$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
$application->bootstrap()->run();