<?php

namespace BunkerMedia\Html;


defined('SYSPATH') or die('No direct script access.');

class TableRow extends LHtml {

    protected $cells = array();

    /**
     * Добавление именованной ячейки в строку
     * @param string $name
     * @return TableRowCell
     */
    function &addCell($name = null) {
        if (!$name) {
            $name = uniqid(microtime(true), true);
        }
        $this->cells[$name] = new TableRowCell();
        return $this->cells[$name];
    }

    /**
     * Получение именованной ячейки
     * @param string $name
     * @return TableRowCell
     */
    function &getCell($name) {
        return $this->cells[$name];
    }

    function __toString() {
        return sprintf('<tr%s>%s</tr>', $this->getAttributes(true), implode('', $this->cells));
    }



}