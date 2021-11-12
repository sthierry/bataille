<?php

namespace Bataille\Controller;

use Bataille\Manager\GameEngineManager;
use Bataille\Etc\DependencyInjectionContainer;
use Bataille\View\GameSettingsView;
use Bataille\View\GameConfrontationView;
use Bataille\View\GameResultView;

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
    private array $arrPlayersVictoryList;

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

        $this->arrPlayersVictoryList = [];
        $arrOutput = [];
        $i = 1;
        if (self::LIMIT === 0)
        {
            $i = 0;
        }
        while (($result = $this->gameEngineManager->confrontCard()) && ($i <= self::LIMIT))
        {
            $arrOutput[] = 'Confronting cards : ' . PHP_EOL;
            foreach ($result->getArrScores() as $arrScore)
            {
                foreach ($arrScore as $score)
                {
                    $arrOutput[] = 'Player ' . $score['player']->getName() . ' picked card ' . $score['pickedCard']->getName() . PHP_EOL;
                }
            }
            $arrOutput[] = 'And the winner of this confrontation is ' . $result->getWinner()->getName() . PHP_EOL;

            $key = json_encode(['id' => $result->getWinner()->getId(), 'name' => $result->getWinner()->getName()]);
            if (isset($this->arrPlayersVictoryList[$key]))
            {
                $this->arrPlayersVictoryList[$key]++;
            }
            else
            {
                $this->arrPlayersVictoryList[$key] = 1;
            }

            if (self::LIMIT !== 0)
            {
                $i++;
            }
            $arrOutput[] = PHP_EOL;
        }

        $gameConfrontationView = $this->dependencyInjectionContainer->instanciateClass(GameConfrontationView::class);
        if (!$gameConfrontationView instanceof GameConfrontationView)
        {
            return;
        }
        $gameConfrontationView->setArrOutput($arrOutput);
        $gameConfrontationView->showView($this);
    }

    public function resultAction()
    {
        arsort($this->arrPlayersVictoryList); //sort playerList by number of victory

        $arrWinner = [];
        $firstNbVictories = null;
        $arrOutput[] = 'FINAL SCORES' . PHP_EOL;
        foreach ($this->arrPlayersVictoryList as $player => $nbVictories)
        {
            $arrOutput[] = json_decode($player)->name . ' won ' . $nbVictories . ' times' . PHP_EOL;
            if ($firstNbVictories === null)
            {
                $arrWinner[] = json_decode($player)->name;
                $firstNbVictories = $nbVictories;
            }
            elseif ($nbVictories === $firstNbVictories)
            {
                $arrWinner[] = json_decode($player)->name;
            }
        }

        $arrOutput[] = PHP_EOL;
        $strOutputWinner = 'And the winner';
        if (count($arrWinner) > 1)
        {
            $strOutputWinner .= 's are ' . implode(' and ', $arrWinner) . ' ex aequo';
        }
        else
        {
            $strOutputWinner .= ' is ' . $arrWinner[0];
        }
        $arrOutput[] = $strOutputWinner . PHP_EOL;

        $gameResultView = $this->dependencyInjectionContainer->instanciateClass(GameResultView::class);
        if (!$gameResultView instanceof GameResultView)
        {
            return;
        }
        $gameResultView->setArrOutput($arrOutput);
        $gameResultView->showView();
    }
}