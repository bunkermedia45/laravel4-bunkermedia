<?php
namespace BunkerMedia\Twig;

use BunkerMedia\Helper\Text;
use BunkerMedia\StaticFiles\Css;
use BunkerMedia\StaticFiles\Js;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class Extension extends Twig_Extension {
    /**
     * {@inheritDoc}
     */
    public function getName() {
        return 'BunkerMedia_Twig_Extension';
    }

    public function getFilters() {
        return [
            new Twig_SimpleFilter('upper', [$this, 'f_upper']),
            new Twig_SimpleFilter('lower', [$this, 'f_lower']),
            new Twig_SimpleFilter('int', [$this, 'intval']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions() {
        return [
            new Twig_SimpleFunction('number_format', [$this, 'f_number_format']),
            new Twig_SimpleFunction('formatter', [$this, 'f_formatter']),
            new Twig_SimpleFunction('helper', [$this, 'f_helper']),
            new Twig_SimpleFunction('carbon', [$this, 'f_carbon']),
            new Twig_SimpleFunction('progressbar_vertical', [$this, 'f_progressbar_vertical']),
            new Twig_SimpleFunction('progressbar', [$this, 'f_progressbar']),
            new Twig_SimpleFunction('accessor', [$this, 'f_accessor']),
            new Twig_SimpleFunction('menu_item_active', [$this, 'f_menu_item_active']),
            new Twig_SimpleFunction('in_menu_path', [$this, 'f_in_menu_path']),
            new Twig_SimpleFunction('faker', [$this, 'f_faker']),
            new Twig_SimpleFunction('substr', [$this, 'f_substr']),
            new Twig_SimpleFunction('var_dump', [$this, 'f_var_dump']),
            new Twig_SimpleFunction('print_r', [$this, 'f_print_r']),
            new Twig_SimpleFunction('arr_get', [$this, 'f_arr_get']),
            new Twig_SimpleFunction('config_get', [$this, 'f_config_get']),
            new Twig_SimpleFunction('plural', [$this, 'f_plural']),
            new Twig_SimpleFunction('css', [$this, 'f_css']),
            new Twig_SimpleFunction('js', [$this, 'f_js']),
            new Twig_SimpleFunction('max', 'max'),
            new Twig_SimpleFunction('min', 'min'),
            new Twig_SimpleFunction('is_array', 'is_array'),
        ];
    }

    function f_number_format($value, $decimals = 2, $dec_point = ',', $thousans_sep = '.') {
        return number_format($value, $decimals, $dec_point, $thousans_sep);
    }

    function f_helper($helper, $method) {
        $class = '\BunkerMedia\Helper\\' . studly_case($helper);
        $args  = array_slice(func_get_args(), 2);
        return call_user_func_array([
            $class,
            $method
        ], $args);
    }

    function f_carbon($date) {
        return \Carbon\Carbon::createFromTimestamp(\BunkerMedia\Helper\Date::todayIfNull($date));
    }

    function f_progressbar_vertical($val, $max, $is_revert = false) {
        if (!$max) {
            return '';
        }
        $percent = $val / $max;
        switch (true) {
            case $percent <= .25;
                $class = !$is_revert ? 'progress-bar-info' : 'progress-bar-danger';
                break;
            case $percent <= .5;
                $class = !$is_revert ? 'progress-bar-success' : 'progress-bar-warning';
                break;
            case $percent <= .75;
                $class = !$is_revert ? 'progress-bar-warning' : 'progress-bar-success';
                break;
            default;
                $class = !$is_revert ? 'progress-bar-danger' : 'progress-bar-info';
                break;
        }
        return \BunkerMedia\Html\Div::factory()
                                    ->addClass('progress vertical wide')
                                    ->setContent(\BunkerMedia\Html\Div::factory()
                                                                      ->setAttribute('style', 'height: ' . intval($val / $max * 100) . '%')
                                                                      ->addClass('progress-bar ' . $class)
                                                                      ->setContent($val . ' / ' . $max));
    }

    function f_progressbar() {
        return \BunkerMedia\Html\Progress::factory();
    }

    function f_accessor($model, $default = null) {
        return \BunkerMedia\Accessor::factory($model, $default);
    }

    function f_in_menu_path($item) {
        return strpos(\Route::currentRouteName(), $item) === 0;
    }

    function f_menu_item_active($url) {
        $url     = '/' . trim($url, '/') . '/';
        $current = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '/';
        $current = '/' . trim($current, '/') . '/';
        return strpos($current, $url) === 0;
    }

    function f_faker($field = null) {
        $faker = \Faker\Factory::create('ru_RU');;
        if ($field) {
            return $faker->{$field};
        }
        return $faker;
    }

    function f_substr($text, $start, $length) {
        return mb_substr($text, $start, $length);
    }

    function f_upper($text) {
        return mb_strtoupper($text);
    }

    function f_lower($text) {
        return mb_strtolower($text);
    }

    function f_var_dump($value) {
        print '<pre>';
        var_dump($value);
        print '</pre>';
    }

    function f_print_r($value) {
        print '<pre>';
        print_r($value);
        print '</pre>';
    }

    function f_arr_get($arr, $key, $default = null) {
        return \Illuminate\Support\Arr::get($arr, $key, $default);
    }

    function f_plural($cnt, $form1, $form2, $form5, $form0 = null) {
        return Text::plural($cnt, $form1, $form2, $form5, $form0 = null);
    }

    function f_formatter($method) {
        $args = array_slice(func_get_args(), 2);
        return call_user_func_array([
            '\BunkerMedia\Formatter',
            $method
        ], $args);
    }

    function f_config_get($key, $default = null) {
        return \Config::get($key, $default);
    }

    function f_css() {
        return Css::instance()->__toString();
    }

    function f_js() {
        return Js::instance()->__toString();
    }
}