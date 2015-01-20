<?php

namespace BunkerMedia;

use Illuminate\Support\Arr as Arr;

class Map
{

    protected        $items = [];
    protected static $instances;

    /**
     *
     * @param type $name
     * @return Map
     */
    static function instance($name = 'default')
    {
        if (!isset(self::$instances[$name])) {
            self::$instances[$name] = new Map();
        }
        return self::$instances[$name];
    }

    function add($access_name, $text, $url, $attrs = [])
    {
        $access_name = trim($access_name, '/');
        $url         = '/' . ltrim($url, '/');
        $access_name = str_replace('/', '.', $access_name);
        $path        = str_replace('.', '._items.', $access_name);
        Arr::set($this->items, $path . '.item.text', $text);
        Arr::set($this->items, $path . '.item.url', $url);
        Arr::set($this->items, $path . '.access_name', $access_name);
        foreach ($attrs as $k => $v) {
            Arr::set($this->items, $path . '.item.' . $k, $v);
        }
        $parents = explode('.', $access_name);
        while (array_pop($parents) != null) {
            if (count($parents)) {
                $this->autoCreateParent(implode('.', $parents));
            }
        }
    }

    /**
     * $access_name = 'admin.plugins.module.config_module';
     * $path = 'admin._items.plugins._items.module._items.config';
     * $default_title = 'Config Module';
     * @param type $access_name
     */
    function autoCreateParent($access_name)
    {
        $path          = str_replace('.', '._items.', $access_name);
        $arr           = explode('.', $access_name);
        $tmp           = array_pop($arr);
        $default_title = ucwords(str_replace('_', ' ', $tmp));
        if (!Arr::get($this->items, $path . '.item')) {
            Arr::set($this->items, $path . '.item.text', $default_title);
            Arr::set($this->items, $path . '.item.url', '#');
        }
        if (!Arr::get($this->items, $path . '.access_name')) {
            Arr::set($this->items, $path . '.access_name', $access_name);
        }
    }

    function getItems()
    {
        return $this->items;
    }

}

