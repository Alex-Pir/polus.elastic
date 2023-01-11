<?php
namespace Polus\Elastic\Settings\Fields;

/**
 * Выпадающий список
 *
 * Class SelectboxField
 * @package Polus\Elastic\Settings\Fields
 */
class SelectboxField extends Fields {

    protected array $items;

    public function __construct(string $code, string $label, int $size = 0) {
        $this->items = [];
        parent::__construct($code, $label, $size);
    }

    /**
     * Получение атрибутов поля
     *
     * @return array
     */
    protected function getAttributes(): array {
        return [self::SELECTBOX_FIELD, $this->items];
    }

    /**
     * Установка допустимых значений в selectbox
     *
     * @param array $items
     * @return $this
     */
    public function setItems(array $items): self {
        $this->items = $items;
        $this->init();
        return $this;
    }
}