<?php

namespace BunkerMedia\Html;

use BunkerMedia\Html\LHtml;

/**
 *
 * @author aberdnikov
 */
class Span extends LHtml {

    protected $_type = 'span';
    protected $_has_closed = true;

    static function factory() {
        return new Span();
    }

}