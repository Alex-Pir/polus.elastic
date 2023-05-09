<?php

namespace Polus\Elastic\Search\Filter;

use Bitrix\Main\Context;
use Bitrix\Main\Request;
use Polus\Elastic\Search\Index\Index;
use Polus\Elastic\Search\SearchQueryBuilder;

class Filter
{
    protected static ?Filter $instance = null;

    protected array $exact = [];
    protected array $range = [];

    protected Index $index;
    protected Request $request;

    protected function __construct(Index $index)
    {
        $this->index = $index;
        $this->request = Context::getCurrent()->getRequest();
    }

    public static function init(Index $index): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static($index);
        }

        return static::$instance;
    }

    public function query(): SearchQueryBuilder
    {
        return $this->index->query();
    }

    public function convertUrlToAggregateFilter(string $url): void
    {

    }

    public function convertUrlToQuery(string $url): SearchQueryBuilder
    {
        $smartParts = explode('/', $url);

        foreach ($smartParts as $smartPart) {
            $smartPart = preg_split("/-(from|to|is|or)-/", $smartPart, -1, PREG_SPLIT_DELIM_CAPTURE);

            $filterElements = [];

            if (!isset($smartParts[0])) {
                continue;
            }

            $smartElementKey = str_replace('-', '.', array_shift($smartPart));

            foreach ($smartPart as $matchKey => $smartElement) {
                if (!isset($smartPart[$matchKey + 1])) {
                    break;
                }

                match ($smartElement) {
                    'from' => $this->query()->where($smartElementKey, '>=', $smartPart[$matchKey + 1]),
                    'to' => $this->query()->where($smartElementKey, '<=', $smartPart[$matchKey + 1]),
                    'is', 'or' => $filterElements[] = $smartPart[$matchKey + 1],
                    default => null
                };
            }

            if ($filterElements) {
                $this->query()->whereIn($smartElementKey, $filterElements);
            }
        }

        return $this->query();
    }

    public function makeSmartUrl($url, $apply, $checkedControlId = false)
    {
        $smartParts = array();


        if ($apply) {
            foreach ($this->arResult["ITEMS"] as $PID => $arItem) {
                $smartPart = array();
                //Prices
                if ($arItem["PRICE"]) {
                    if ($arItem["VALUES"]["MIN"]["HTML_VALUE"] <> '') {
                        $smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
                    }
                    if ($arItem["VALUES"]["MAX"]["HTML_VALUE"] <> '') {
                        $smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
                    }
                }

                if ($smartPart) {
                    array_unshift($smartPart, "price-" . $arItem["URL_ID"]);

                    $smartParts[] = $smartPart;
                }
            }

            foreach ($this->arResult["ITEMS"] as $PID => $arItem) {
                $smartPart = array();
                if ($arItem["PRICE"]) {
                    continue;
                }

                //Numbers && calendar == ranges
                if (
                    $arItem["PROPERTY_TYPE"] == "N"
                    || $arItem["DISPLAY_TYPE"] == "U"
                ) {
                    if ($arItem["VALUES"]["MIN"]["HTML_VALUE"] <> '') {
                        $smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
                    }
                    if ($arItem["VALUES"]["MAX"]["HTML_VALUE"] <> '') {
                        $smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
                    }
                } else {
                    foreach ($arItem["VALUES"] as $key => $ar) {
                        if (
                            (
                                $ar["CHECKED"]
                                || $ar["CONTROL_ID"] === $checkedControlId
                            )
                            && mb_strlen($ar["URL_ID"])
                        ) {
                            $smartPart[] = $ar["URL_ID"];
                        }
                    }
                }

                if ($smartPart) {
                    if ($arItem["CODE"]) {
                        array_unshift($smartPart, toLower($arItem["CODE"]));
                    } else {
                        array_unshift($smartPart, $arItem["ID"]);
                    }

                    $smartParts[] = $smartPart;
                }
            }
        }

        if (!$smartParts) {
            $smartParts[] = array("clear");
        }

        return str_replace("#SMART_FILTER_PATH#", implode("/", $this->encodeSmartParts($smartParts)), $url);
    }
}