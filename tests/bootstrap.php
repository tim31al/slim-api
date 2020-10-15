<?php

use DI\ContainerBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(
    __DIR__ . '/../config/settings.php',
    __DIR__.'/../config/settings.local.php'
);
$builder->addDefinitions(__DIR__ . '/../config/services.php');

return $builder->build();
