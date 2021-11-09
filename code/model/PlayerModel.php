<?php

namespace Bataille\Model;

use Bataille\Model\DeckModel;

/**
 * @name \Bataille\Model\PlayerModel
 */
class PlayerModel extends AbstractModel
{
    /**
     * @var string
     */
    private string $name;

    private DeckModel $deck;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \Bataille\Model\DeckModel
     */
    public function getDeck()
    {
        return $this->deck;
    }

    /**
     * @param \Bataille\Model\DeckModel $deck
     * @return $this
     */
    public function setDeck($deck)
    {
        $this->deck = $deck;
        return $this;
    }

}