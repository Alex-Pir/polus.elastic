<?php
namespace Polus\Elastic\Settings\Fields;

/**
 * Поле для HTML
 *
 * Class HtmlField
 * @package Polus\Elastic\Settings\Fields
 */
class HtmlField extends Fields
{
    /** @var string  */
    protected $html;

    public function __construct(string $code, string $label = "") {
        $this->html = "";
        parent::__construct($code, $label);
    }

    /**
     * Инициализация поля, заполнение массива
     * $this->optionDescription
     */
    protected function init() {
        $this->optionDescription = [$this->code, $this->label, $this->html, $this->getAttributes()];
    }

    /**
     * Получение атрибутов поля
     *
     * @return array
     */
    protected function getAttributes(): array {
        return [self::HTML_FIELD];
    }

    /**
     * Установка значения для поля
     *
     * @param string $html
     * @return $this
     */
    public function setValue(string $html): self {
        $this->html = $html;
        $this->init();
        return $this;
    }
}