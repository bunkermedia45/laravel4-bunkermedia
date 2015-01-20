<?php

namespace BunkerMedia\Html;

use BunkerMedia\Html\LHtml;
/**
 *
 * @author aberdnikov
 */
class Img extends LHtml {

    protected $_type = 'img';
    protected $_has_closed = false;

    /**
     * @return Img
     */
    static function factory() {
        return new Img();
    }

    function setSrc($src) {
        $this->setAttribute('src', $src);
        return $this;
    }

}