<?php

namespace Bataille\View;

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
        $this->resolveInputs();
        $mixed->gameAction($this);
    }
}