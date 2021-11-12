<?php

namespace Bataille;

use Bataille\Test\GameEngineTest;
use Bataille\Etc\DependencyInjectionContainer;

spl_autoload_register(function ($className) {
    $arrCLassPath = explode('\\', $className);
    include __DIR__ . '/' . strtolower($arrCLassPath[1]) . '/' . $arrCLassPath[2] . '.php';
});

$dependencyInjectionContainer = new DependencyInjectionContainer();
$gameEngineTest = $dependencyInjectionContainer->instanciateClass(GameEngineTest::class);
$gameEngineTest->testConfrontCard();