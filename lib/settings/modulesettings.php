<?php

namespace Polus\Elastic\Settings;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use CAdminMessage;
use CAdminTabControl;
use Polus\Elastic\Entity\OptionTable;

Loc::loadMessages(__FILE__);

/**
 * Класс, предназначенный для хранения настроек модуля
 * и методы для отрисовки и сохранения формы
 *
 * Class ModuleSettings
 * @package Citrus\Options
 */
final class ModuleSettings
{
    /** @var self|null экземпляр класса */
    private static $instance = null;

    /** @var string ID модуля */
    private $moduleId;

    /** @var array массив с табами */
    private $tabs;

    /** @var \Bitrix\Main\HttpRequest|\Bitrix\Main\Request объект запроса */
    private $request;

    private function __construct()
    {
        $this->moduleId = null;
        $this->tabs = [];
        $this->request = Context::getCurrent()->getRequest();
    }

    /**
     * Получение экземпляра класса
     *
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Установка ID модуля
     *
     * @param string $moduleId
     */
    public function setModuleId(string $moduleId)
    {
        $this->moduleId = $moduleId;
    }

    /**
     * Получение ID модуля
     *
     * @throws SystemException
     */
    public function getModuleId(): string
    {
        if ($this->moduleId === null) {
            throw new SystemException("Не задан ID модуля");
        }

        return $this->moduleId;
    }

    /**
     * Добавление таба
     *
     * @param Tab $tab
     */
    public function addTab(Tab $tab)
    {
        $this->tabs[] = $tab;
    }

    /**
     * Вывод страницы
     */
    public function viewSettingsPage()
    {
        $this->saveForm();
        $this->drawForm();
    }

    /**
     * Отрисовка формы
     */
    protected function drawForm()
    {
        $tabsDescription = [];

        foreach ($this->tabs as $tab) {
            $tabsDescription[] = $tab->getTabDescription();
        }

        $tabControl = new CAdminTabControl("tabControl", $tabsDescription);

        $tabControl->Begin();
        ?>
        <form method="post"
              action="<?= sprintf('%s?mid=%s&lang=%s', $this->request->getRequestedPage(), urlencode($this->request->get("mid")), LANGUAGE_ID) ?>"><?php
        echo bitrix_sessid_post();

        foreach ($this->tabs as $tab) {
            $tabControl->BeginNextTab();

            $tab->draw();
        }

        $tabControl->Buttons();
        ?><input type="submit"
                 name="save"
                 value="<?= Loc::getMessage("MODULE_SETTINGS_SAVE") ?>"
                 title="<?= Loc::getMessage("MODULE_SETTINGS_SAVE_TITLE") ?>"
                 class="adm-btn-save"
        />
        <input type="submit"
               name="restore"
               title="<?= Loc::getMessage("MODULE_SETTINGS_RESTORE_DEFAULTS") ?>"
               onclick="return confirm('<?= addslashes(Loc::getMessage("MODULE_SETTINGS_RESTORE_DEFAULTS_WARNING")) ?>')"
               value="<?= Loc::getMessage("MODULE_SETTINGS_RESTORE_DEFAULTS") ?>"
        /><?php
        $tabControl->End();
        ?></form><?php
    }

    /**
     * Сохранение формы
     *
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentNullException
     */
    protected function saveForm()
    {

        list($save, $restore, $apply) = [$this->request->get("save"), $this->request->get("restore"), $this->request->get("apply")];

        if (!($save || $restore || $apply) || !$this->request->isPost() || !check_bitrix_sessid()) {
            return;
        }

        if ($restore) {
            Option::delete($this->getModuleId());
            CAdminMessage::ShowMessage(array(
                "MESSAGE" => Loc::getMessage("MODULE_SETTINGS_OPTIONS_RESTORED"),
                "TYPE" => "OK",
            ));
        } else {
            foreach ($this->tabs as $tab) {
                $tab->save();
            }

            CAdminMessage::ShowMessage(array(
                "MESSAGE" => Loc::getMessage("MODULE_SETTINGS_OPTIONS_SAVED"),
                "TYPE" => "OK",
            ));
        }

        OptionTable::getEntity()->cleanCache();
    }

    /**
     * Получение полей для настроек прав доступа
     *
     * @return string
     * @throws SystemException
     */
    public function getAccessRightsHtml(): string {
        global $APPLICATION, $USER;

        $module_id = $this->getModuleId();

        list($save, $apply, $GROUPS, $RIGHTS) = [
            $this->request->get("save"),
            $this->request->get("apply"),
            $this->request->get("GROUPS"),
            $this->request->get("RIGHTS")
        ];

        if (($save || $apply) && $this->request->isPost() && check_bitrix_sessid()) {
            /** @var $Update - флаг, была ли нажата кнопка сохранения */
            $Update = $save || $apply;
            $REQUEST_METHOD =  $this->request->getRequestMethod();
        }

        /** Сохранение настроек прав доступа */
        ob_start();
        require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");

        return ob_get_clean();
    }

    /**
     * Получение сохраняемого значения
     *
     * @param string $code
     * @return array|false|string
     */
    public function getSavedValue(string $code) {
        $value = $this->request->get($code);

        if (!$value) {
            return false;
        }

        return $value;
    }

    /**
     * Сохранение данных в поле
     *
     * @param string $code
     * @param $value
     */
    public function saveValue(string $code, $value): void {
        if (!empty($code)) {
            $_REQUEST[$code] = $value;
        }
    }
}