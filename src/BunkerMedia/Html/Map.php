<?php

    namespace BunkerMedia\Html;

    use BunkerMedia\Html\LHtml;
    use BunkerMedia\Html\Table_Row_Cell;

    defined('SYSPATH') or die('No direct script access.');

    class Map extends LHtml {

        protected $areas = array();

        /**
         * Factory table element
         *
         * @return Map
         */
        static function factory() {
            return new Map();
        }

        /**
         * Добавление именованной ячейки в строку
         *
         * @param string $name
         * @return Area
         */
        function &add_area($name = null) {
            if (!$name) {
                $name = uniqid(microtime(true), true);
            }
            $this->areas[$name] = Area::factory();
            return $this->areas[$name];
        }

        /**
         * Получение именованной ячейки
         *
         * @param string $name
         * @return Area
         */
        function &get_area($name) {
            return $this->areas[$name];
        }

        /**
         * @param $value
         * @return Map
         */
        function set_name($value) {
            $this->setAttribute('name', $value);
            return $this;
        }

        /**
         * @param $value
         * @return map
         */
        function set_id($value) {
            return parent::set_id($value);
        }

        function __toString() {
            return sprintf('<map%s>%s</map>', $this->getAttributes(true), implode('', $this->areas));
        }

    }