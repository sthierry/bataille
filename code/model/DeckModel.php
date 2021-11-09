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

    /**
     * @param \Bataille\Model\CardModel $card
     */
    public function addCard(CardModel $card)
    {
        $this->cards[] = $card;
    }

    /**
     * @param array $stack
     */
    public function addStackOfCard(array $stack)
    {
        $this->cards = array_merge($this->cards, $stack);
    }

    /**
     * @param int $numberOfCardToPick
     * @return array
     */
    public function pickTopStackOfCard(int $numberOfCardToPick): array
    {
        //splitting deck into stacks of the asked number of cards
        $arrayChunk = array_chunk($this->cards, $numberOfCardToPick);
        //putting the top stack of cards aside
        $stack = array_shift($arrayChunk);
        //merging together the others stacks to rebuild the deck without the top stack
        $this->cards = array_merge_recursive($arrayChunk);
        return $stack;
    }

    /**
     * @return \Bataille\Model\CardModel|null
     */
    public function pickTopCard(): CardModel|null
    {
        return array_shift($this->cards);
    }
}