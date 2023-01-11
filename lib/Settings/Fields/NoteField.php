<?php
namespace Polus\Elastic\Settings\Fields;

/**
 * Текстовые уведомления
 *
 * Class NoteField
 * @package Polus\Elastic\Settings\Fields
 */
class NoteField extends Fields {

    /** @var string текст уведомления */
    protected $note;

    public function __construct(string $note) {
        $this->note = $note;
        $this->init();
    }

    /**
     * Инициализация поля, заполнение массива
     * $this->optionDescription
     */
    protected function init() {
        $this->optionDescription = $this->getAttributes();
    }

    /**
     * Получение атрибутов поля
     *
     * @return mixed
     */
    protected function getAttributes(): array {
        return [self::NOTE_FIELD => $this->note];
    }
}