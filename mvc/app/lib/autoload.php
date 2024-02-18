<?php

$autoload_directories = [
    'app/lib/',
    'app/controllers/',
    'app/models/'
];

spl_autoload_register(function ($class) use ($autoload_directories) {
    foreach ($autoload_directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            include $file;
            break;
        }
    }
});
?>