<?php

namespace Bataille\Model;

/**
 * @name \Bataille\Model\CardModel
 */
class CardModel extends AbstractModel
{
    /**
     * @var int
     */
    private int $powerness;

    /**
     * @var string
     */
    private string $name;

    /**
     * @return int
     */
    public function getPowerness(): int
    {
        return $this->powerness;
    }

    /**
     * @param int $powerness
     * @return $this
     */
    public function setPowerness(int $powerness)
    {
        $this->powerness = $powerness;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

}