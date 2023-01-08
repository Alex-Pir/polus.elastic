<?php
namespace Polus\Elastic\Settings\Fields;

/**
 * Строковое поле
 *
 * Class StringField
 * @package Polus\Elastic\Settings\Fields
 */
class StringField extends Fields
{
    public function __construct(string $code, string $label, int $size = 40) {
        parent::__construct($code, $label, $size);
    }

    /**
     * Получение атрибутов поля
     *
     * @return array
     */
    protected function getAttributes(): array {
        return [self::TEXT_FIELD, $this->size];
    }
}