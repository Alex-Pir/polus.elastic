<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

define("CITFACT_REST_MODULE", basename(__DIR__)); // citfact.rest
// вместо __DIR__ проверяем путь с помощью getLocalPath чтобы избежать проблем с символическими ссылками
define("CITFACT_REST_BX_ROOT", strpos(getLocalPath("modules/" . CITFACT_REST_MODULE), "/local") === 0 ? "/local" : BX_ROOT);
define("CITFACT_REST_MODULE_DIR", CITFACT_REST_BX_ROOT . "/modules/" . CITFACT_REST_MODULE);

if (file_exists( __DIR__ . "/vendor/autoload.php")) {
    require_once __DIR__ . "/vendor/autoload.php";
}
