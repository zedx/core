<?php

namespace ZEDx\Utils\Translation;

use App;
use Exception;
use File;
use Themes;

class Translator
{
    /**
     * Merge translations.
     *
     * @param string $type
     * @param array  $defaultTrans
     *
     * @return array
     */
    public static function merge($type, array $defaultTrans = [])
    {
        if (in_array($type, [
            'frontend', 'email', 'backend',
            'pagination', 'passwords',
            'validation', 'auth',
        ])) {
            try {
                $trans = self::apply($type, array_dot($defaultTrans));
            } catch (Exception $e) {
                $trans = [];
            }

            return $trans;
        }
    }

    /**
     * Apply merge.
     *
     * @param string $type
     * @param array  $defaultTrans
     *
     * @return array
     */
    protected static function apply($type, array $defaultTrans)
    {
        $userCustomTrans = [];
        $templateCustomTrans = [];

        $locale = App::getLocale();
        $activeTheme = Themes::getActive();

        if (File::exists(base_path("themes/{$activeTheme}/lang/{$locale}/{$type}.php"))) {
            $templateCustomTrans = array_dot(require_once(base_path("themes/{$activeTheme}/lang/{$locale}/{$type}.php")));
        }

        if (File::exists(base_path("resources/lang/core/{$locale}/{$type}.php"))) {
            $userCustomTrans = array_dot(require_once(base_path("resources/lang/core/{$locale}/{$type}.php")));
        }

        return array_merge($defaultTrans, $templateCustomTrans, $userCustomTrans);
    }
}
