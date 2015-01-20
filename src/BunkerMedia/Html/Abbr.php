<?php

namespace BunkerMedia\Html;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author aberdnikov
 */
class Abbr extends Button {

    protected $_type = 'abbr';
    protected $_has_closed = true;

    /**
     * @return Abbr
     */
    static function factory() {
        return new Abbr();
    }

    function asInitialism() {
        $this->addClass('initialism');
        return $this;
    }

}