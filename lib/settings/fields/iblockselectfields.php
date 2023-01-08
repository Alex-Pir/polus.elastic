<?php

namespace Polus\Elastic\Settings\Fields;

use Bitrix\Iblock\IblockTable;
use Exception;
use Polus\Elastic\Constants;
use Polus\Elastic\Traits\HasModules;

class IblockSelectFields extends MultiSelectboxField
{
    use HasModules;

    public function __construct(string $code, string $label, int $size = 0)
    {
        parent::__construct($code, $label, $size);
        $this->setItems($this->getIblockSelectList());
    }

    /**
     * Получение списка информационных блоков
     *
     * @return array
     */
    protected function getIblockSelectList(): array {
        $result = [];

        try {
            static::includeIblockModule();

            $iblocks = IblockTable::getList([
                "select" => ["ID", "NAME"],
                "cache" => ["ttl" => Constants::CACHE_TIME]
            ])->fetchAll();

            $result = $this->prepareOptionList($iblocks, ["ID" => "ID", "NAME" => "NAME"], true);
        } catch(Exception $ex) {
            AddMessage2Log($ex->getMessage());
        }

        return $result;
    }

    /**
     * Обработка выборки значений из базы для настроек модуля
     *
     * @param array $variants
     * @param array $select
     * @param bool $allowEmpty
     * @return array
     */
    protected function prepareOptionList(array $variants, array $select, bool $allowEmpty = false): array {
        $result = [];

        if ($allowEmpty) {
            $result[0] = "";
        }

        if (!isset($select["ID"]) || !isset($select["NAME"])) {
            return $result;
        }

        $id = $select["ID"];
        $name = $select["NAME"];

        foreach ($variants as $variant) {
            if (!isset($variant[$id]) || !isset($variant[$name])) {
                continue;
            }

            $result[$variant[$id]] = "[{$variant[$id]}]{$variant[$name]}";
        }

        return $result;
    }
}