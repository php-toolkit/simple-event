<?php
/**
 * phpunit
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

spl_autoload_register(function ($class) {
    $file = null;

    if (0 === strpos($class,'Toolkit\SimpleEvent\Example\\')) {
        $path = str_replace('\\', '/', substr($class, strlen('Toolkit\SimpleEvent\Example\\')));
        $file = dirname(__DIR__) . "/example/{$path}.php";
    } elseif (0 === strpos($class,'Toolkit\SimpleEvent\Test\\')) {
        $path = str_replace('\\', '/', substr($class, strlen('Toolkit\SimpleEvent\Test\\')));
        $file = __DIR__ . "/{$path}.php";
    } elseif (0 === strpos($class,'Toolkit\SimpleEvent\\')) {
        $path = str_replace('\\', '/', substr($class, strlen('Toolkit\SimpleEvent\\')));
        $file = dirname(__DIR__) . "/src/{$path}.php";
    }

    if ($file && is_file($file)) {
        include $file;
    }
});
