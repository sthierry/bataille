<?php

namespace Bataille\View;

use Bataille\Controller\GameController;

/**
 * @name \Bataille\View\GameSettingsView
 */
class GameSettingsView extends abstractView
{
    /**
     * @inheritDoc
     */
    public function showView(mixed $mixed = null)
    {
        if (!$mixed instanceof GameController)
        {
            echo 'Error in GameResultView : $mixed bust be an instance of ' . GameController::class;
        }
        $this->resolveInputs();
        $mixed->gameAction($this);
    }
}