<?php

namespace Bataille\Controller;

use Bataille\Manager\GameEngineManager;
use Bataille\Model\PlayerModel;
use Bataille\Etc\DependencyInjectionContainer;

/**
 * @name \Bataille\Controller\GameController
 */
class GameController
{
    private GameEngineManager $gameEngineManager;
    private DependencyInjectionContainer $dependencyInjectionContainer;

    /**
     * @param \Bataille\Manager\GameEngineManager $gameEngineManager
     * @param \Bataille\Etc\DependencyInjectionContainer $dependencyInjectionContainer
     */
    public function __construct(GameEngineManager $gameEngineManager, DependencyInjectionContainer $dependencyInjectionContainer)
    {
        $this->gameEngineManager = $gameEngineManager;
        $this->dependencyInjectionContainer = $dependencyInjectionContainer;
    }

    public function indexAction()
    {
        $this->gameEngineManager->populateMainDeck();
        $playersArray = [];
        for ($i=1;$i<=3;$i++) {
            /**
             * @var PlayerModel
             */
            $player = $this->dependencyInjectionContainer->instanciateClass(PlayerModel::class);
            if(!$player instanceof PlayerModel) {return;}
            $player->setId($i);
            $player->setName('player '.$i);
            $playersArray[] = $player;
        }

        $this->gameEngineManager->distributeDeck($playersArray);

        echo PHP_EOL;
    }
}