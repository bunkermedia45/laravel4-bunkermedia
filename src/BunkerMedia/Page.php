<?php
namespace BunkerMedia;

use Illuminate\Support\Arr;
/*
Класс для управления страницей
*/
class Page {
    static           $breadcrumbs = [];
    static protected $values      = '';

    static function addBreadCrumb($title, $url = '#', $menuitem = null) {
        self::$breadcrumbs[] = [
            'url'   => $url,
            'title' => $title,
        ];
        if (!$menuitem) {
            $menuitem = str_replace('/', '.', trim($url, '/'));
        }
        self::title($title);
        self::h1($title);
    }

    static function page_data() {
        return self::$values;
    }

    static function _($key, $value = null) {
        if ($value) {
            self::$values[$key] = $value;
        }
        return Arr::get(self::$values, $key);
    }

    static function title($value = null) {
        return self::_(__FUNCTION__, $value);
    }

    static function h1($value = null) {
        return self::_(__FUNCTION__, $value);
    }
}
