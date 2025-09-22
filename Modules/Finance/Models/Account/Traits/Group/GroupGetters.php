<?php

namespace Modules\Finance\Models\Account\Traits\Group;

trait GroupGetters
{

    public static function getTranslatedNatures()
    {
        $result = [];

        foreach (self::$natures as $nature) {
            $result [] = [
                'key' => $nature,
                'value' => $nature,
            ];
        }

        return $result;
    }

    public static function getTranslatedTypes()
    {
        $result = [];

        foreach (self::$types as $type) {
            $result[] = [
                'key' => $type,
                'value' => $type,
            ];
        }

        return $result;
    }
}
