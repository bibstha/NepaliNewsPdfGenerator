<?php
date_default_timezone_set('Asia/Kathmandu');
if (!defined('TEST_PATH')) define('TEST_PATH', dirname(__FILE__));
if (!defined('APP_PATH')) define('APP_PATH', realpath(TEST_PATH . '/../src'));
if (!defined('FILE_PATH')) define('FILE_PATH', realpath(TEST_PATH . '/../files'));
set_include_path( 
    realpath(APP_PATH) .
    PATH_SEPARATOR .
    get_include_path()
);