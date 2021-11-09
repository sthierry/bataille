<?php

namespace Bataille\Model;

use Bataille\Model\CardModel;

/**
 * @name \Bataille\Model\DeckModel
 */
class DeckModel extends AbstractModel
{
    /**
     * @var array of CardModel
     */
    private array $cards;

    /**
     * @return array
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @param array $cards
     * @return $this
     */
    public function setCards(array $cards)
    {
        $this->cards = $cards;
        return $this;
    }

    public function shuffle(): bool
    {
        return shuffle($this->cards);
    }
}