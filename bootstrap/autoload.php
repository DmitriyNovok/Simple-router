<?php

spl_autoload_register(function ($class) {
    $file = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace(array('_', '\\'), '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
