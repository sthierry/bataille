<?php

namespace Bataille\Test;

use Bataille\Manager\GameEngineManager;
use Bataille\Etc\DependencyInjectionContainer;
use Bataille\Model\PlayerModel;
use Bataille\Model\DeckModel;
use Bataille\Model\CardModel;

/**
 * @name \Bataille\Test\GameEngineTest
 */
class GameEngineTest
{
    private GameEngineManager $gameEngineManager;
    private DependencyInjectionContainer $dependencyInjectionContainer;
    private int $idIndex;

    public function __construct(GameEngineManager $gameEngineManager, \Bataille\Etc\DependencyInjectionContainer $dependencyInjectionContainer)
    {
        $this->gameEngineManager = $gameEngineManager;
        $this->dependencyInjectionContainer = $dependencyInjectionContainer;
        $this->idIndex = 1;
    }

    /**
     * @throws \ReflectionException
     */
    public function testConfrontCard()
    {
        echo 'Testing if power 2 card win over power 1 card' . PHP_EOL;
        $winningPlayer = $this->initializePlayerWithCard(2);
        $losingPlayer = $this->initializePlayerWithCard(1);
        $this->gameEngineManager->setPlayerArray([$winningPlayer, $losingPlayer]);
        $result = $this->gameEngineManager->confrontCard();
        $winnerId = $result->getWinner()->getId();
        if ($winnerId === $winningPlayer->getId())
        {
            echo 'Test ok' . PHP_EOL;
        }
        else
        {
            echo 'Test fail' . PHP_EOL;
            var_dump($result);
            echo PHP_EOL;
        }

        echo 'Testing if cards with idendical power provoke null result' . PHP_EOL;
        $player1 = $this->initializePlayerWithCard(2);
        $player2 = $this->initializePlayerWithCard(2);
        $this->gameEngineManager->setPlayerArray([$player1, $player2]);
        $result = $this->gameEngineManager->confrontCard();
        if (!$result)
        {
            echo 'Test ok' . PHP_EOL;
        }
        else
        {
            echo 'Test fail' . PHP_EOL;
            var_dump($result);
            echo PHP_EOL;
        }
    }

    /**
     * @param int $cardPowerness
     * @return \Bataille\Model\PlayerModel
     * @throws \ReflectionException
     */
    private function initializePlayerWithCard(int $cardPowerness): PlayerModel
    {
        $card = $this->dependencyInjectionContainer->instanciateClass(CardModel::class);
        $card->setId($this->idIndex);
        $card->setName('Card with powerness = ' . $cardPowerness);
        $card->setPowerness($cardPowerness);

        $player = $this->dependencyInjectionContainer->instanciateClass(PlayerModel::class);
        $player->setId($this->idIndex);
        $player->setName('Player with card powerness = ' . $cardPowerness);
        $player->getDeck()->addCard($card);

        return $player;
    }
}