<?php

namespace BunkerMedia\Html;

use BunkerMedia\Html\LHtml;

/**
 *
 * @author aberdnikov
 */
class Strong extends LHtml {

    protected $_type = 'strong';
    protected $_has_closed = true;

    static function factory() {
        return new Strong();
    }

}