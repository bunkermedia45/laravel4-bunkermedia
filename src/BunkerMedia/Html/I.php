<?php

namespace BunkerMedia\Html;

use BunkerMedia\Html\LHtml;

/**
 *
 * @author aberdnikov
 */
class I extends LHtml {

    protected $_type = 'i';
    protected $_has_closed = true;
    
    static function factory() {
        return new I();
    }

}