<?php

    namespace BunkerMedia\Html;

    use BunkerMedia\Html\Button;

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     *
     * @author aberdnikov
     */
    class Area extends LHtml {

        protected $_type = 'area';
        protected $_has_closed = true;

        /**
         * @return A
         */
        static function factory() {
            $area = new Area();
            $area->setAttribute('shape', 'poly');
            return $area;
        }

        function setCoords($value) {
            $this->setAttribute('coords', $value);
            return $this;
        }

        function setShape($type = 'poly') {
            $this->setAttribute('shape', $type);
            return $this;
        }

    }