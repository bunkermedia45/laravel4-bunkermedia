<?php

namespace BunkerMedia\Html;

use BunkerMedia\Html\LHtml;

/**
 *
 * @author aberdnikov
 */
class Strike extends LHtml {

    protected $_type = 'strike';
    protected $_has_closed = true;

    /**
     * @return Strike
     */
    static function factory() {
        return new Strike();
    }

}