<?php
/**
 * Функции для работы с мультиязычностью в БД
 */

if (!function_exists('dbtrans')) {
    /**
     * Возвращает перевод для языковой строки
     * @param  string $key    ключ языковой строки вида {model}.{id}.{field} или {module}.{key}.{field}
     * @param  string $locale ключ языка (ru, en...)
     * @return text         Перевод строки, либо ключ строки, если перевод не найден
     */
    function dbtrans($key, $locale=null)
    {
        $locale = prepareLocale($locale);
        $tmpAr = explode('.', $key);
        if (count($tmpAr) == 3) {
            list($model, $uid, $field) = $tmpAr;
        } else if (count($tmpAr) == 2) {
            list($model, $uid) = $tmpAr;
            $field = $uid;
        }
        
        if ($model != prepareModel($model)) {
            $model = prepareModel($model);            
        }

        $key = implode('.', [$model, $uid, $field]);
        
        $langs = getDbLang($model, $uid, $locale);

        if (isset($langs[$locale][$model]) && isset($langs[$locale][$model][$uid]) && isset($langs[$locale][$model][$uid][$field])) {
            return $langs[$locale][$model][$uid][$field];
        }

        return $locale . '.' . $key;
    }
}

if (!function_exists('getDbLang')) {
    /**
     * Получаем массив языковых строк для языка, модели и записи
     * @param  string $model  модель
     * @param  string $uid    ID записи
     * @param  string $locale ключ языка или NULL
     * @return array         массив языковых строк
     */
    function getDbLang($model='db', $uid='app', $locale=null)
    {
        $locale = prepareLocale($locale);
        $langs[$locale] = [];

        // if (\Cache::has('dbtrans')) {
        //     dump('Cache');
        //     $langs = \Cache::get('dbtrans');
        //     return $langs;
        // } else {
        //     dump('DB');
            $dbLang = \App\LangData::where('lang', $locale)
                            ->where('model', strtolower($model))
                            ->where('uid', $uid)
                            ->first();
            if ($dbLang) {
                $langAr = json_decode($dbLang->json, true);
                $langs[$locale][$model][$uid] = $langAr;
                // \Cache::put('dbtrans', $langs);
                return $langs;
            }
        // }
        
        return $langs;
    }
}

if (!function_exists('prepareLocale')) {
    /**
     * Приведение языкового ключа к нужному виду
     * @param  string $locale языковой ключ или NULL
     * @return string         языковой ключ, не NULL
     */
    function prepareLocale($locale=null)
    {
        if (!$locale && !$locale = locale()) {
            $locale = config('app.fallback_locale');
        }
        return $locale;
    }
}

if (!function_exists('prepareModel')) {
    function prepareModel($model) {
        if (strpos($model, '\\') !== false) {
            $nsAr = explode('\\', $model);
            $model = strtolower(array_pop($nsAr));
        } else {
            $model = strtolower($model);
        }
        return $model;
    }
}