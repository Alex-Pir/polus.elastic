<?php

namespace Polus\Elastic\Traits;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;
use Polus\Elastic\Constants;
use Polus\Elastic\Entity\OptionTable;

Loc::loadMessages(__FILE__);

/**
 * Трейт предназначен для получения параметра настроек модуля
 *
 * Trait HasModuleOption
 * @package Citfact\Rest\Traits
 */
trait HasModuleOption
{
    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function getAllModuleOptions(): array
    {
        $options = OptionTable::getList([
            'filter' => [
                'MODULE_ID' => Constants::MODULE_ID
            ],
            'select' => ['NAME', 'VALUE'],
            'cache' => ['ttl' => Constants::CACHE_TIME]
        ])->fetchAll();

        foreach ($options as $option) {
            $result[$option['NAME']] = $option['VALUE'];
        }

        return $result ?? [];
    }

    /**
     * Получение параметра настроек модуля
     *
     * @param string $code
     * @param $default
     * @return string|null
     */
    public static function getModuleOption(string $code, $default = null): ?string
    {
        return static::getOtherModuleOption(Constants::MODULE_ID, $code, $default);
    }

    /**
     * Получение параметра настроек любого модуля
     *
     * @param string $moduleId
     * @param string $code
     * @param null $default
     * @return string|null
     */
    public static function getOtherModuleOption(string $moduleId, string $code, $default = null): ?string
    {
        try {
            return Option::get($moduleId, $code, $default);
        } catch (Exception $ex) {
            AddMessage2Log($ex->getMessage());
            return $default;
        }
    }

    /**
     * Получение параметра настроек модуля в виде массива
     *
     * @param string $code
     * @param array $default
     * @param string $separator
     * @return array
     */
    public static function getModuleOptionArray(string $code, array $default = [], string $separator = ','): array
    {
        try {
            $result = Option::get(Constants::MODULE_ID, $code, null);

            if ($result == null) {
                return $default;
            }

            return explode($separator, Option::get(Constants::MODULE_ID, $code, $default));
        } catch (Exception $ex) {
            AddMessage2Log($ex->getMessage());
            return $default;
        }
    }
}
