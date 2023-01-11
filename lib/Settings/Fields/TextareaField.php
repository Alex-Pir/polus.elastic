<?php
namespace Polus\Elastic\Settings\Fields;

/**
 * Многострочное текстовое поле
 *
 * Class StringField
 * @package Polus\Elastic\Settings\Fields
 */
class TextAreaField extends Fields
{
    public function __construct(string $code, string $label, array $size = [10, 45]) {
        parent::__construct($code, $label, $size);
    }

    /**
     * Получение атрибутов поля
     *
     * @return array
     */
    protected function getAttributes(): array {
        return array_merge([self::TEXTAREA_FIELD], $this->size);
    }
}