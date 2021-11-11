<?php

namespace Bataille\Manager;

use Bataille\Model\ConfrontationResultModel;

/**
 * @name \Bataille\Manager\GameEngineManagerInterface
 */
interface GameEngineManagerInterface
{

    /**
     * @param array $playersArray
     * @return \Bataille\Model\ConfrontationResultModel|null
     */
    public function confrontCard(array $playersArray) : ConfrontationResultModel|null;
}