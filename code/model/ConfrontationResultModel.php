<?php

namespace Bataille\Model;

use Bataille\Model\PlayerModel;

/**
 * @name \Bataille\Model\ConfrontationResultModel
 */
class ConfrontationResultModel extends AbstractModel
{
    /**
     * @var \Bataille\Model\PlayerModel
     */
    private PlayerModel $winner;

    /**
     * Associative array Score => [Card => Player]
     * @var array
     */
    private array $arrScores;

    /**
     * @return array
     */
    public function getArrScores()
    {
        return $this->arrScores;
    }

    /**
     * @param array $arrScores
     * @return $this
     */
    public function setArrScores($arrScores)
    {
        $this->arrScores = $arrScores;
        return $this;
    }

    /**
     * @return \Bataille\Model\PlayerModel
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param \Bataille\Model\PlayerModel $winner
     * @return $this
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
        return $this;
    }
}