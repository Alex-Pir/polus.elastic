Классы предназначены для того, чтобы облегчить создание страницы с настройками модуля в файле options.php

Примеры использования:
```php
/** Создание таба */
new Tab("edit1", Loc::getMessage("MAIN_TAB_SET"), Loc::getMessage("MAIN_TAB_TITLE_SET"));

/** Создание текстового поля */
new StringField("form_field_name", Loc::getMessage("FIELD_LABEL"));

/** Создание выпадающего списка */
(new SelectboxField("form_field_name", Loc::getMessage("FIELD_LABEL")))
                ->setItems(["1" => 1, "2" => 2]);

/** Добавление полей в таб */
new Tab("edit1", Loc::getMessage("MAIN_TAB_SET"), Loc::getMessage("MAIN_TAB_TITLE_SET")))
            ->addField(new StringField("form_field_name", Loc::getMessage("FIELD_LABEL")));

/** Добавление таба в общий список табов */
$moduleSettings = ModuleSettings::getInstance();
$moduleSettings->setModuleId("module_id");
$moduleSettings->addTab($tab);

/** Таб с настройками доступа */
$moduleSettings = ModuleSettings::getInstance();
$rightTab = new Tab("rightsTab", "Доступ", "Права доступа");
try {
    $rightTab->addField((new HtmlField("rights"))->setValue($moduleSettings->getAccessRightsHtml()));
} catch (SystemException $ex) {
    AddMessage2Log($ex->getMessage());
}

/** вывод всех табов на странице */
$moduleSettings = ModuleSettings::getInstance();
//обязательно должен быть добавлен id модуля
$moduleSettings->setModuleId("module_id");
//отрисовка табов вместе с полями
$moduleSettings->viewSettingsPage();
