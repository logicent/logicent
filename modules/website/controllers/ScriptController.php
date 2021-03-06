<?php

namespace website\controllers;

use crudle\setup\controllers\base\BaseSettingsController;
use website\models\WebsiteScriptForm;

/**
 * ScriptController for the `WebsiteScriptForm` model
 */
class ScriptController extends BaseSettingsController
{
    public function modelClass(): string
    {
        return WebsiteScriptForm::class;
    }
}
