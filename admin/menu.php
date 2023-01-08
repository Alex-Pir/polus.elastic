<?php

use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

/**
 * используем старый интерфейс, а не $asset->addCss(),
 * так как он не работает в данном файле инициализации меню
 */
//$GLOBALS["APPLICATION"]->SetAdditionalCSS(CITFACT_REST_MODULE_DIR . "/admin/css/style.css");

return array(
    array(
        'parent_menu' => 'global_menu_sitecore',
        'sort' => 300,
        'text' => 'Test',
        'url' => '',
        'more_url' => array(

        )
    )
);
