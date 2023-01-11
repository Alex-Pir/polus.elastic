<?php

if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
	require_once __DIR__ . "/../vendor/autoload.php";
}

if (file_exists(__DIR__ . "/../.env")) {
	$dotenv = Dotenv\Dotenv::create(__DIR__ . "/../");
	$dotenv->load();
}

$_SERVER["DOCUMENT_ROOT"] = getenv("BITRIX_PATH") ? getenv("BITRIX_PATH") : "/var/www/html";

define("PUBLIC_AJAX_MODE", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("NOT_CHECK_PERMISSIONS", true);
define("DisableEventsCheck", true);
define("NO_AGENT_CHECK", true);


if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php")) {
	echo "error: Не найдено ядро bitrix. Возможно \$_SERVER[\"DOCUMENT_ROOT\"] определена неверно - {$_SERVER["DOCUMENT_ROOT"]}\n";
	return;
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (!\Bitrix\Main\Loader::includeModule("polus.search")) {
    echo "Модуль не подключен\n";
    return;
}


//require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");
//return;
