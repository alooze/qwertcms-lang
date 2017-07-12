# QWERTCMS-LANG

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Данная библиотека является модулем для alooze/qwertcms-base. Ее предназначение - дать возможность хранить языковые строки в БД и управлять этими строками через админку. 

Обычный способ использования языковых строк при этом не изменяется. 

## Архитектура

1) миграция lang_data
2) модель LangData
3) trait alooze/QwertCms/Translatable/Translatable
4) функции хелперы в файле Helpers/translate.php
5) роуты для управления языковыми строками в админке routes/qwertcms-lang.php (нужен include в роуты)
6) контроллер для админки LangController
7) шаблоны из views/langs/
8) настройка в админке 
        'НАСТРОЙКИ',
        [
            'text' => 'Языковые строки',
            'url' => 'admin/langs',
            'icon' => 'cubes',//'braille',
            'icon_color' => 'green'
        ],
9) папка public для копирования в public/vendors/qwertcms-lang

## Замечания по реализации

1) Уникальность названий полей в таблице lang_data не отслеживается
2) Сами ключи языков не хранятся в БД, только в конфиге
3) Для сохранения моделей и их переводов необходимо к модели подключить trait Translatable, указать атрибут protected $translatable = ['name']; и использовать один из двух методов lcreate($data, $locale=null, $keyField='id') или lsave($locale=null, $keyField='id')

## Install

Via Composer

``` bash
$ composer require alooze/qwertcms-lang
```
Описание установки и использования будет сделано позднее

## Credits

- [alooze][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/alooze/.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/alooze//master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/alooze/.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/alooze/.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/alooze/.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/alooze/
[link-travis]: https://travis-ci.org/alooze/
[link-scrutinizer]: https://scrutinizer-ci.com/g/alooze//code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/alooze/
[link-downloads]: https://packagist.org/packages/alooze/
[link-author]: https://github.com/alooze
[link-contributors]: ../../contributors
