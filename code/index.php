<?php

namespace Bataille;

use Bataille\Controller\GameController;
use Bataille\Etc\DependencyInjectionContainer;

spl_autoload_register(function ($className) {
    $arrCLassPath = explode('\\', $className);
    include __DIR__.'/'.strtolower($arrCLassPath[1]).'/'.$arrCLassPath[2].'.php';
});

$dependencyInjectionContainer = new DependencyInjectionContainer();
$gameController = $dependencyInjectionContainer->instanciateClass(GameController::class);
//$gameController = new GameController();
$gameController->indexAction();