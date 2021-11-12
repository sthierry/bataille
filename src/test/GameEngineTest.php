<?php

namespace Bataille\Test;

use Bataille\Manager\GameEngineManager;
use Bataille\Etc\DependencyInjectionContainer;

/**
 * @name \Bataille\Test\GameEngineTest
 */
class GameEngineTest
{
    private GameEngineManager $gameEngineManager;
    private DependencyInjectionContainer $dependencyInjectionContainer;

    public function __construct(GameEngineManager $gameEngineManager)
    {
        $this->gameEngineManager = $gameEngineManager;
    }

    public function testConfrontCard()
    {

    }
}