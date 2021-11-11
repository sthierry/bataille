<?php

namespace Bataille\Controller;

use Bataille\Manager\GameEngineManager;
use Bataille\Etc\DependencyInjectionContainer;
use Bataille\View\GameSettingsView;
use Bataille\View\GameConfrontationView;

/**
 * @name \Bataille\Controller\GameController
 */
class GameController
{
    public const NB_PLAYER = 2;
    /**
     * 0 = until player decks are empty
     * 1 && > 1 = number of confrontations
     */
    public const LIMIT = 0;

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
        $this->settingsAction();
    }

    public function settingsAction()
    {
        $this->gameEngineManager->populateMainDeck();

        $nbPlayer = self::NB_PLAYER;
        $gameSettingsView = $this->dependencyInjectionContainer->instanciateClass(GameSettingsView::class);
        if (!$gameSettingsView instanceof GameSettingsView)
        {
            return;
        }
        $arrInput = [];
        for ($i = 1; $i <= $nbPlayer; $i++)
        {
            $arrInput[$i] = ['question' => 'Enter player ' . $i . ' name', 'defaultAnswer' => 'player ' . $i];
        }
        $gameSettingsView->setArrInput($arrInput);
        $gameSettingsView->showView($this);
    }

    /**
     * @param \Bataille\View\GameSettingsView $gameSettingsView
     */
    public function gameAction(GameSettingsView $gameSettingsView)
    {
        $this->gameEngineManager->populatePlayerArray($gameSettingsView->getArrInput());
        $this->gameEngineManager->distributeDeck();

        $arrOutput = [];
        $i = 1;
        if (self::LIMIT === 0)
        {
            $i = 0;
        }
        while (($result = $this->gameEngineManager->confrontCard()) && ($i <= self::LIMIT))
        {
            $arrOutput[] = " " . PHP_EOL;
            $arrOutput[] = "Confronting cards : " . PHP_EOL;
            foreach ($result->getArrScores() as $arrScore)
            {
                foreach ($arrScore as $score)
                {
                    $arrOutput[] = 'Player ' . $score['player']->getName() . ' picked card ' . $score['pickedCard']->getName() . PHP_EOL;
                }
            }
            $arrOutput[] = 'And the winner of this confrontation is ' . $result->getWinner()->getName() . PHP_EOL;

            if (self::LIMIT !== 0)
            {
                $i++;
            }
        }
        $gameConfrontationView = $this->dependencyInjectionContainer->instanciateClass(GameConfrontationView::class);
        if (!$gameConfrontationView instanceof GameConfrontationView)
        {
            return;
        }
        $gameConfrontationView->setArrOutput($arrOutput);
        $gameConfrontationView->showView();
    }
}