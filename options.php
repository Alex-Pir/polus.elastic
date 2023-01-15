<?php

/**
 * @var \CUser $USER
 * @var \CMain $APPLICATION
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Polus\Elastic\Constants;
use Polus\Options\ModuleSettings;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

Loc::loadMessages(__FILE__);

$moduleId = 'polus.elastic';

if (!defined('ADMIN_MODULE_NAME') || ADMIN_MODULE_NAME !== $moduleId) {
	define("ADMIN_MODULE_NAME", $moduleId);
}

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
    return false;
}

try {
	if (!Loader::includeModule($moduleId)) {
		ShowError(Loc::getMessage("POLUS_ELASTIC_OPTION_E_MODULE_NOT_INSTALL"));
	}

    $moduleSettings = ModuleSettings::getInstance();
    $moduleSettings->setModuleId($moduleId);

    $options = Polus\Elastic\Options::getInstance();
    $tabs = $options->getTabs();

    foreach ($tabs as $tab) {
        $moduleSettings->addTab($tab);
    }

    $moduleSettings->viewSettingsPage();
} catch (LoaderException $ex) {
	ShowError($ex->getMessage());
}
