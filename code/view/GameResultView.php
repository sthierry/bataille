<?php

namespace Bataille\View;

/**
 * @name \Bataille\View\GameResultView
 */
class GameResultView extends abstractView
{

    /**
     * @inheritDoc
     */
    function showView(mixed $mixed = null)
    {
        $this->displayOutputs();
    }
}