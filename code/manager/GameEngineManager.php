<?php

namespace Bataille\Manager;

use Bataille\Model\PlayerModel;
use Bataille\Model\DeckModel;
use Bataille\Model\CardModel;
use Bataille\Model\ConfrontationResultModel;
use Bataille\Etc\DependencyInjectionContainer;

/**
 * @name \Bataille\Manager\GameEngineManager
 */
class GameEngineManager implements GameEngineManagerInterface
{
    /**
     * @var \Bataille\Model\DeckModel
     */
    private DeckModel $mainDeck;

    private DependencyInjectionContainer $dependencyInjectionContainer;

    /**
     * @var array
     */
    private array $playerArray;

    /**
     * @param \Bataille\Model\DeckModel $mainDeck
     * @param \Bataille\Etc\DependencyInjectionContainer $dependencyInjectionContainer
     */
    public function __construct(DeckModel $mainDeck, \Bataille\Etc\DependencyInjectionContainer $dependencyInjectionContainer)
    {
        $this->mainDeck = $mainDeck;
        $this->playerArray = [];
        $this->dependencyInjectionContainer = $dependencyInjectionContainer;
    }

    public function confrontCard(): ConfrontationResultModel|null
    {
        $playersArray = $this->playerArray;
        $arrOfarrResult = [];
        $arrResult = [];
        $playerCount = count($playersArray);
        foreach ($playersArray as $player)
        {
            $pickedCard = $this->pickCard($player);
            if (!$pickedCard) {
                $playerCount--;
                continue;
            }

            $result = ['pickedCard' => $pickedCard, 'player' => $player];
            if(!isset($arrResult[0])) {
                $arrResult[] = $result;
                continue;
            }
            $arrResult = $this->insertResultInSortedByCardPowernessArray($result, $arrResult);
        }

        if($playerCount < 2) {
            return null;
        }

        $arrOfarrResult[] = $arrResult;
        $confrontationResultModel = new ConfrontationResultModel();
        $confrontationResultModel->setArrScores($arrOfarrResult);
        $confrontationResultModel->setWinner($arrResult[0]['player']);
        return $confrontationResultModel;
    }

    /**
     * @param array $result
     * @param array $arrResult
     * @return array
     */
    private function insertResultInSortedByCardPowernessArray(array $result, array $arrResult): array
    {
        $pickedCard = $result['pickedCard'];
        $player = $result['player'];
        $tmpArrResult = [];
        $inserted = false;
        foreach ($arrResult as $innerResult) {
            if ($pickedCard->getPowerness() === $innerResult['pickedCard']->getPowerness()) {
                $arrOfarrResult[] = $this->confrontCard([$player, $innerResult['player']]);
                $tmpArrResult[] = $result;
                $inserted = true;
                continue;
            }
            if(!$inserted && ($pickedCard->getPowerness() > $innerResult['pickedCard']->getPowerness())) {
                $tmpArrResult[] = $result;
                $inserted = true;
            }
            $tmpArrResult[] = $innerResult;
        }
        if(!$inserted) {
            $tmpArrResult[] = $result;
        }
        return $tmpArrResult;
    }

    private function pickCard(PlayerModel $player): CardModel|null
    {
        return $player->getDeck()->pickTopCard();
    }

    /**
     * Return true if distribution is done
     * @param array $playersArray
     * @return bool
     */
    public function distributeDeck(): bool
    {
        $playersArray = $this->playerArray;
        $this->mainDeck->shuffle();//On mélange les cartes avant de les distribuer.
        $numberOfPlayers = count($playersArray);
        $numberOfCardsInMainDeck = $this->mainDeck->getNumberOfCardsInTheDeck();
        if ($numberOfPlayers>$numberOfCardsInMainDeck) {
            return false;
        }
        foreach($playersArray as $player) {
            if(!$player instanceof PlayerModel) {
                var_dump('fail instance');
                return false;
            }
            $player->getDeck()->addStackOfCard($this->mainDeck->pickTopStackOfCard(floor($numberOfCardsInMainDeck/$numberOfPlayers)));
        }

        return true;
    }

    /**
     * Populating main decks with generated cards
     */
    public function populateMainDeck()
    {
        for($i=1;$i<=52;$i++) {
            $card = new CardModel();
            $card->setId($i);
            $card->setName($i);
            $card->setPowerness($i);
            $this->mainDeck->addCard($card);
        }
    }

    /**
     * Generating players
     * @param array $playerNamesArray [ref => ['question' => string, 'answer' => string, 'defaultAnswer' => string]
     */
    public function populatePlayerArray(array $playerNamesArray)
    {
        foreach ($playerNamesArray as $key => $playerInfos)
        {
            $player = $this->dependencyInjectionContainer->instanciateClass(PlayerModel::class);
            $player->setId($key);
            $player->setName($playerInfos['answer']);
            $this->playerArray[] = $player;
        }
    }

}