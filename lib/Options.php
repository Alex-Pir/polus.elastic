<?php

namespace Polus\Elastic;

use Bitrix\Main\Localization\Loc;
use Polus\Elastic\Traits\HasModuleOption;
use Polus\Elastic\Traits\HasModules;
use Polus\Options\Fields\TextAreaField;
use Polus\Options\Tab;

Loc::loadMessages(__FILE__);

/**
 * Класс, предназначенный для получения табов с настройками модуля
 *
 * Class Option
 * @package Polus\Elastic
 */
class Options
{
    use HasModules;
    use HasModuleOption;

    /** @var Options|null экземпляр класса */
    private static ?Options $instance = null;

    /** доступ к коснтруктору закрыт */
    private function __construct()
    {
    }

    /**
     * Создание экземпляра класса
     *
     * @return Options
     */
    public static function getInstance(): Options
    {
        if (is_null(static::$instance)) {
            static::$instance = new Options();
        }

        return static::$instance;
    }

    /**
     * Возвращает табы с настройками модуля
     *
     * @return Tab[]
     */
    public function getTabs(): array
    {
        return [
            $this->mainTab()
        ];
    }

    protected function mainTab(): Tab
    {
        $tab = new Tab('edit1', 'Настройки', 'Основные настройки');
        $tab->addField(new TextAreaField(Constants::ELASTIC_HOST, 'Хосты'));

        return $tab;
    }
}