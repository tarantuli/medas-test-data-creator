<?php

declare(strict_types=1);

use Medas\TestDataCreator\TestDataCreatorPackage;
use Medas\ServiceManager\{ServiceConfig, ServiceManager};

chdir(__DIR__);

new ServiceManager(function (): ServiceConfig {
    $config = new ServiceConfig();

    $config->addPackages([
        TestDataCreatorPackage::instance(),
    ]);

    return $config;
});
