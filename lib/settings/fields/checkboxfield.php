<?php
namespace Polus\Elastic\Settings\Fields;

/**
 * Поле в виде чекбокса
 *
 * Class CheckboxField
 * @package Polus\Elastic\Settings\Fields
 */
class CheckboxField extends Fields
{
    public function __construct(string $code, string $label) {
        parent::__construct($code, $label);
    }

    /**
     * Получение атрибутов поля
     *
     * @return array
     */
    protected function getAttributes(): array {
       return [self::CHECKBOX_FIELD];
    }
}