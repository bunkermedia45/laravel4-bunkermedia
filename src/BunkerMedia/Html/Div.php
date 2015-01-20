<?php

namespace BunkerMedia\Html;

use BunkerMedia\Html\LHtml;

/**
 *
 * @author aberdnikov
 */
class Div extends LHtml {

    protected $_type = 'div';
    protected $_has_closed = true;
    
    static function factory() {
        return new Div(); 
    }

}