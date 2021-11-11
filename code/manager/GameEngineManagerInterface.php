<?php

namespace Bataille\Manager;

use Bataille\Model\PlayerModel;

/**
 * @name \Bataille\Manager\GameEngineManagerInterface
 */
interface GameEngineManagerInterface
{

    /**
     * @param array $playersArray
     * @return \Bataille\Model\PlayerModel|null
     */
    public function confrontCard(array $playersArray) : PlayerModel|null;
}