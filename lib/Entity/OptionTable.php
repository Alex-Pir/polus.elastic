<?php
namespace Polus\Elastic\Entity;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Event;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;

class OptionTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_option';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new StringField(
                'MODULE_ID',
                [
                    'primary' => true,
                    'validation' => [__CLASS__, 'validateModuleId']
                ]
            ),
            new StringField(
                'NAME',
                [
                    'primary' => true,
                    'validation' => [__CLASS__, 'validateName']
                ]
            ),
            new TextField('VALUE'),
            new StringField(
                'DESCRIPTION',
                [
                    'validation' => [__CLASS__, 'validateDescription']
                ]
            ),
            new StringField(
                'SITE_ID',
                [
                    'validation' => [__CLASS__, 'validateSiteId']
                ]
            ),
        ];
    }

    /**
     * Returns validators for MODULE_ID field.
     *
     * @return array
     */
    public static function validateModuleId()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for NAME field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(null, 100),
        ];
    }

    /**
     * Returns validators for DESCRIPTION field.
     *
     * @return array
     */
    public static function validateDescription()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }

    /**
     * Returns validators for SITE_ID field.
     *
     * @return array
     */
    public static function validateSiteId()
    {
        return [
            new LengthValidator(null, 2),
        ];
    }
}