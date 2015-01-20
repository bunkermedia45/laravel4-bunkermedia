<?php

namespace BunkerMedia\Html;

class TableRowCell extends LHtml {
    protected $_type = 'td';
    protected $_has_closed = true;

    function __construct($val = null) {
        $this->set_content($val);
    }

    /**
     *
     * @return TableRowCell
     */
    function asTh() {
        $this->_type = 'th';
        return $this;
    }

    /**
     *
     * @return TableRowCell
     */
    function asTd() {
        $this->type = 'td';
        return $this;
    }

    /**
     *
     * @param type $val
     * @return TableRowCell
     */
    function setColspan($val) {
        $val = intval($val);
        if ($val > 1) {
            $this->setAttribute('colspan', $val);
        }
        return $this;
    }

    /**
     *
     * @param type $val
     * @return TableRowCell
     */
    function setRowspan($val) {
        $val = intval($val);
        if ($val > 1) {
            $this->setAttribute('rowspan', $val);
        }
        return $this;
    }
}