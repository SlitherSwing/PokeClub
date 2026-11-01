<?php

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

$logger = new Logger('pokeclub');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../../var/log/pokeclub.log', Level::Error));

return $logger;
