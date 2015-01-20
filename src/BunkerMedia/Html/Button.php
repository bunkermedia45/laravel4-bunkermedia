<?php

    namespace BunkerMedia\Html;

    use BunkerMedia\Html\LHtml;
    use BunkerMedia\Html\Icon;

    /**
     *
     * @author aberdnikov
     */
    class Button extends LHtml {

        protected $_type = 'button';
        protected $_has_closed = true;
        protected $_icon = null;

        /**
         * @return \BunkerMedia\Html\Button
         */
        static function factory() {
            return new Button();
        }

        /**
         * @return $this
         */
        function sizeLarge() {
            $this
                ->sizeClear()
                ->addClass('btn-lg');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function sizeClear() {
            return $this
                ->addClass('btn')
                ->removeClass('btn-lg btn-sm btn-xs');
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function sizeSmall() {
            $this
                ->sizeClear()
                ->addClass('btn-sm');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function sizeExtraSmall() {
            $this
                ->sizeClear()
                ->addClass('btn-xs');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function styleSuccess() {
            $this
                ->styleClear()
                ->addClass('btn-success');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function styleWarning() {
            $this
                ->styleClear()
                ->addClass('btn-warning');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function styleDanger() {
            $this->styleClear()->addClass('btn-danger');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function style_inverse() {
            $this->styleClear()->addClass('btn-inverse');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function style_link() {
            $this->styleClear()->addClass('btn-link');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function style_default() {
            $this->styleClear()->addClass('btn-default');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function style_primary() {
            $this->styleClear()->addClass('btn-primary');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function disabled_on() {
            $this->addClass('disabled');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function disabled_off() {
            $this->removeClass('disabled');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function active_on() {
            $this->addClass('active');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function active_off() {
            $this->removeClass('active');
            return $this;
        }

        /**
         * @return \BunkerMedia\Html\Button
         */
        function styleClear() {
            return $this
                ->addClass('btn')
                ->removeClass('btn-default btn-primary btn-link btn-default btn-inverse btn-danger btn-warning btn-success');
        }

    }
