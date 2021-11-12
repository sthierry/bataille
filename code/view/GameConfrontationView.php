<?php

namespace Bataille\View;

use Bataille\Controller\GameController;

/**
 * @name \Bataille\View\GameConfrontationView
 */
class GameConfrontationView extends abstractView
{

    /**
     * @inheritDoc
     */
    function showView(mixed $mixed = null)
    {
        if(!$mixed instanceof GameController) {
            echo 'Error in GameResultView : $mixed bust be an instance of '.GameController::class;
        }
        $this->displayOutputs();
        $mixed->resultAction();
    }
}