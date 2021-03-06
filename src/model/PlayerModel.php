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

    public function __construct(DeckModel $deck)
    {
        $this->deck = $deck;
    }

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

}