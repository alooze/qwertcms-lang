<?php

namespace alooze\QwertCms\Translatable;
use App\LangData;

trait Translatable
{
    /**
     * Состояние текущей модели
     * @var boolean
     */
    protected $alreadyTranslated = false;

    /**
     * Заполняем атрибуты объекта модели данными из таблицы языков в БД
     * @param  string $locale   ключ языка
     * @param  string $keyField первичный ключ модели
     * @return $this
     */
    public function translate($locale=null, $keyField='id')
    {
        $locale = prepareLocale($locale);
        if ($this->alreadyTranslated && $this->alreadyTranslated === $locale) {
            return $this;
        }
        // dump('!'.$locale);
        foreach ($this->attributes as $attrName => $attrVal) {
            if (in_array($attrName, $this->translatable)) {
                $this->$attrName = dbtrans(__CLASS__ . '.' . $this->$keyField . '.' . $attrName, $locale);
            }
        }
        $this->alreadyTranslated = $locale;
        return $this;
    }

    /**
     * Сохранение модели в БД. Атрибуты, указанные как tranlatable будут заменяться 
     * на ключи вида model.id.field, их настроящие значения будут сохраняться в отдельной 
     * таблице с языковыми строками.
     * @param  string $locale ключ языка
     * @return bool
     */
    public function lsave($locale=null, $keyField='id') {
        $this->save();
        $locale = prepareLocale($locale);
        $forSave = [];

        foreach ($this->attributes as $attrName => $attrVal) {
            if (in_array($attrName, $this->translatable)) {
                $forSave[$attrName] = $attrVal;
                $this->$attrName = prepareModel(__CLASS__) . '.' . $this->$keyField . '.' . $attrName;
            }
        }

        LangData::updateOrCreate(
            ['lang' => $locale, 'model' => prepareModel(__CLASS__), 'uid' => $this->$keyField],
            ['json' => json_encode($forSave), 'uid' => $this->$keyField]
        );
        // $this->alreadyTranslated = false;
        return $this->save();
    }

    public static function lcreate($data, $locale=null, $keyField='id')
    {
        $obj = self::create($data);
        $obj->lsave($locale, $keyField);
        return $obj;
    }
}
