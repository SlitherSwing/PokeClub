<?php

spl_autoload_register(function (string $class): void {

    $prefix = 'PokeClub\\';
    $baseDirectory = __DIR__ . '/../';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDirectory . str_replace('\\', '/', $relativeClass) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
