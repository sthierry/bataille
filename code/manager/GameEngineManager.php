<?php

namespace Bataille\Manager;

use Bataille\Model\PlayerModel;
use Bataille\Model\DeckModel;
use Bataille\Model\CardModel;

/**
 * @name \Bataille\Manager\GameEngineManager
 */
class GameEngineManager implements GameEngineManagerInterface
{
    /**
     * @var \Bataille\Model\DeckModel
     */
    private DeckModel $mainDeck;

    /**
     * @param \Bataille\Model\DeckModel $mainDeck
     */
    public function __construct(DeckModel $mainDeck)
    {
        $this->mainDeck = $mainDeck;
    }

    public function confrontCard(array $playersArray): PlayerModel|null
    {
        // TODO: Implement confrontCard() method.
        return null;
    }

    private function pickCard(PlayerModel $player)
    {
        return $player->getDeck()->pickTopCard();
    }

    /**
     * Return true if distribution is done
     * @param array $playersArray
     * @return bool
     */
    public function distributeDeck(array $playersArray): bool
    {
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

}