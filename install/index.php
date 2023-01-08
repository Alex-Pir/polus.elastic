<?php

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Citfact\Rest\Agents\Export\CheckTimeExportAgent;
use CItfact\Rest\Agents\FavoriteCategoryAgent;
use Citfact\Rest\Agents\Report\ReportAgentGenerator;
use Citfact\Rest\Entity\Export\ExportManager;
use Citfact\Rest\Entity\Filter\AttachedPropertiesManager;
use Citfact\Rest\Entity\Report\ReportManager;

Loc::loadMessages(__FILE__);

/**
 * Class POLUS_ELASTIC
 *
 * Класс описывющий модуль, используется для установки модуля
 */
class POLUS_ELASTIC extends CModule
{
	/**
	 * @var string код модуля
	 */
	public $MODULE_ID = "polus.elastic";
	/**
	 * @var string права модуля
	 */
	public $MODULE_GROUP_RIGHTS = 'N';
	/**
	 * @var string версия модуля
	 */
	public $MODULE_VERSION = '1.0.0';
	/**
	 * @var string дата создания или обновления модуля в формате Y-m-d
	 */
	public $MODULE_VERSION_DATE = '';
	/**
	 * @var string название модуля
	 */
	public $MODULE_NAME = '';
	/**
	 * @var string описание модуля
	 */
	public $MODULE_DESCRIPTION = '';
	/**
	 * @var string автор модуля
	 */
	public $PARTNER_NAME = '';
	/**
	 * @var string ссылка на сайт автора
	 */
	public $PARTNER_URI = '';
	/**
	 * @var string[] массив с моудлями, от которых звисит модуль
	 */
	protected $SUB_MODULE = [];

	/**
	 * citrus_module_noname constructor.
	 */
	public function __construct()
	{
		$this->MODULE_NAME = Loc::getMessage("POLUS_ELASTIC_F_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("POLUS_ELASTIC_F_DESCRIPTION");

		$this->PARTNER_NAME = Loc::getMessage("POLUS_ELASTIC_F_COMPANY_NAME");
		$this->PARTNER_URI = Loc::getMessage("POLUS_ELASTIC_F_COMPANY_URI");

		$this->loadVersion();
	}

	/**
	 * Установка модуля
	 */
	public function doInstall()
	{
		global $APPLICATION;

		try {
			$this->loadSubModules();

			Main\ModuleManager::registerModule($this->MODULE_ID);
			Loader::includeModule($this->MODULE_ID);

			$this->installDb();
			$this->installEvents();
			$this->installFiles();
			$this->installEntities();
		} catch (Exception $ex) {
			Main\ModuleManager::unRegisterModule($this->MODULE_ID);
			$APPLICATION->ThrowException($ex->getMessage());
		}

		$APPLICATION->IncludeAdminFile(
			Loc::getMessage("POLUS_ELASTIC_F_INSTALL_TITLE",
				array("#MODULE#" => $this->MODULE_NAME, "#MODULE_ID#" => $this->MODULE_ID)), __DIR__ . "/step1.php"
		);
	}

	/**
	 * Удаление моудля и всех его составляющих
	 */
	public function doUninstall()
	{
		global $APPLICATION, $step;

		$step = (int)$step;

		try {
			if ($step <= 1) {
				$APPLICATION->IncludeAdminFile(
					Loc::getMessage("POLUS_ELASTIC_F_INSTALL_TITLE",
						array("#MODULE#" => $this->MODULE_NAME)), __DIR__ . "/uninstall/step1.php"
				);
			} else {
				Loader::includeModule($this->MODULE_ID);

				$request = Application::getInstance()->getContext()->getRequest();
				$saveData = ("Y" == $request->get("save_module_db"));
				$saveOptions = ("Y" == $request->get("save_module_option"));
				if (!$saveData) {
					$this->unInstallDB();
				}

				if (!$saveOptions) {
					Main\Config\Option::delete($this->MODULE_ID);
				}

				$this->uninstallEntities($saveData);
				$this->uninstallEvents();
				$this->uninstallFiles();

				Main\ModuleManager::unRegisterModule($this->MODULE_ID);

				$APPLICATION->IncludeAdminFile(
					Loc::getMessage("POLUS_ELASTIC_F_INSTALL_TITLE",
						array("#MODULE#" => $this->MODULE_NAME)), __DIR__ . "/uninstall/step2.php"
				);
			}
		} catch (Exception $ex) {
			$APPLICATION->ThrowException($ex->getMessage());
			$APPLICATION->IncludeAdminFile(
				Loc::getMessage("POLUS_ELASTIC_F_INSTALL_TITLE",
					array("#MODULE#" => $this->MODULE_NAME)), __DIR__ . "/uninstall/step1.php"
			);
		}
	}

	/**
	 * Загрузка данных в базы или создание необходимых таблиц
	 * @return void
	 */
	function installDB()
	{

	}

	/**
	 *
	 * Удаление данных из базы
	 * @return void
	 */
	function unInstallDB()
	{

	}

	/**
	 * Добалвение (регистрация) обработчиков
	 *
	 * @return void
	 */
	public function installEvents()
	{
        $eventManager = Main\EventManager::getInstance();

        /**
         * Изменение выдачи ошибки 404
         */
        /*$eventManager->registerEventHandler(
            "main",
            "OnEpilog",
            $this->MODULE_ID,
            "Citfact\\Rest\\Events\\Epilog\\Handler", "onEpilogHandler"
        );*/
	}

	/**
	 * Удаление всех зависимостей между модулями (события)
	 *
	 * @return void
	 */
	public function uninstallEvents()
	{
        $eventManager = Main\EventManager::getInstance();

        /*$eventManager->unRegisterEventHandler(
            "main", "OnEpilog",
            $this->MODULE_ID,
            "Citfact\\Rest\\Events\\Epilog\\Handler", "onEpilogHandler"
        );*/
	}

	/**
	 * Копирование файлов модуля
	 */
	public function installFiles()
	{
	    global $APPLICATION;
/*
		CopyDirFiles(__DIR__ . "/components", Main\Application::getDocumentRoot() . CITFACT_REST_BX_ROOT . "/components/" . CITFACT_REST_MODULE, true, true);
		CopyDirFiles(__DIR__ . "/routes", Main\Application::getDocumentRoot() . CITFACT_REST_BX_ROOT . "/routes/", true, true);
		if (!file_exists(Main\Application::getDocumentRoot() . "/bitrix/.settings_extra.php")) {
            CopyDirFiles(__DIR__ . "/bitrix", Main\Application::getDocumentRoot() . "/bitrix/", false, true);
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("CITFACT_REST_CANNOT_COPY_SETTING_FILE"));
        }*/
	}

	/**
	 * Удаление файлов модуля
	 */
	public function uninstallFiles()
	{/*
		DeleteDirFilesEx(CITFACT_REST_BX_ROOT . "/components/" . CITFACT_REST_MODULE);
		DeleteDirFilesEx(CITFACT_REST_BX_ROOT . "/routes/");*/
	}

	/**
	 * Создание сущностей модуля (таблицы, обработчики, файлы)
	 */
	public function installEntities()
	{
	}

	/**
	 * Удаление сущностей модуля (таблицы, обработчики, файлы)
	 *
	 * @param false $saveData
	 */
	public function uninstallEntities($saveData = false)
	{
	}

	/**
	 * Установить данные по версии модуля
	 */
	protected function loadVersion()
	{
		$arModuleVersion = array(
			"VERSION" => "1.0.0",
			"VERSION_DATE" => DateTime::createFromFormat('Y-m-d', time()),
		);

		@include __DIR__ . '/version.php';

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}
	}

	/**
	 * Подключение к БД
	 *
	 * @return Main\DB\Connection
	 */
	protected function getConnection()
	{
		return Main\Application::getInstance()->getConnection();
	}

	/**
	 * Подключение дополнительных модулей
	 * @throws Exception
	 */
	protected function loadSubModules()
	{
		try {
			foreach ($this->SUB_MODULE as $module) {
				if (true === Loader::includeModule($module))
					continue;

				throw new Exception(Loc::getMessage("POLUS_ELASTIC_ERROR_SUBMODULE", [
					'#MODULE_NAME#' => $this->MODULE_ID,
					'#SUB_MODULE_NAME#' => $module
				]));
			}
		} catch (Exception $ex) {
			throw new Exception($ex->getMessage());
		}
	}

	/**
	 * Описание списка уровней доступа к модулю
	 *
	 * @return string[][]
	 */
	public function GetModuleRightList(): array
	{
		return [
			"reference_id" => ["AU", "1C"],
			"reference" => [
                "[AU] " . Loc::getMessage("POLUS_ELASTIC_PERM_AU"),
				"[1C] " . Loc::getMessage("POLUS_ELASTIC_PERM_1C"),
			]
		];
	}
}
