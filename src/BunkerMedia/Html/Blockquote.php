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
class Blockquote extends LHtml {

    protected $_type = 'blockquote';
    protected $_has_closed = true;
    protected $_author = '';

    static function factory() {
        return new Blockquote();
    }

    function setAuthor($author) {
        $this->_author = $author;
        return $this;
    }

    function getContent() {
        $ret = '';
        $ret .='<p>' . $this->_content . '</p>';
        if ($this->_author) {
            $ret .='<small>' . $this->_author . '</small>';
        }
        return $ret;
    }

}