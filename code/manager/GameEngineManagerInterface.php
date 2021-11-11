<?php

namespace Bataille\Manager;

use Bataille\Model\ConfrontationResultModel;

/**
 * @name \Bataille\Manager\GameEngineManagerInterface
 */
interface GameEngineManagerInterface
{

    /**
     * @return \Bataille\Model\ConfrontationResultModel|null
     */
    public function confrontCard() : ConfrontationResultModel|null;
}