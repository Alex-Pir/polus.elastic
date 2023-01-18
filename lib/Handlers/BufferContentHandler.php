<?php

namespace Polus\Elastic\Handlers;

use Bitrix\Iblock\PropertyTable;

class BufferContentHandler
{
    private const SEARCHABLE_CONTENT = 'id="frm_prop"';

    public static function propertySettingsHandler(&$content): void
    {
        if (!str_contains($content, static::SEARCHABLE_CONTENT)) {
            return;
        }

        $content = str_replace(
            '</tbody></table></div></form>',
            '<tr>
		<td width="40%"><label for="PROPERTY_USER_TYPE_SETTINGS[ELASTIC]">Использовать в эластике:</label></td>
		<td>
			<input type="checkbox" id="PROPERTY_USER_TYPE_SETTINGS[ELASTIC]" name="PROPERTY_USER_TYPE_SETTINGS[ELASTIC]" value="Y">
		</td>
	</tr></tbody></table></div></form>',
            $content
        );

        $content = str_replace(
            '<input type="hidden" id="PROPERTY_PROPERTY_TYPE" name="PROPERTY_PROPERTY_TYPE" value="S">',
            '<input type="hidden" id="PROPERTY_PROPERTY_TYPE" name="PROPERTY_PROPERTY_TYPE" value="S:elastic">',
            $content
        );
    }

    public static function customerPropertyType(): array
    {
        return [
            "PROPERTY_TYPE" => PropertyTable::TYPE_STRING,
            "USER_TYPE" => 'elastic',
            "convertToDB" => [static::class, 'convertToDB']
        ];
    }

    /**
     * Преобразование значения перед сохранением в базу данных
     *
     * @param array $arProperty
     * @param array $value
     * @return array|string
     */
    public static function convertToDB(array $arProperty, array $value): array|string
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logValue.log', print_r($value, true) . "\n", FILE_APPEND);
        $value['VALUE'] = base64_encode(serialize($value['VALUE']));

        return $value;
    }
}