<?php

namespace BunkerMedia\Html;

class ProgressBar extends \BunkerMedia\Html\Div {

    public $size;

    function __construct($size = 100, $type = null) {
        $this->size = $size;
        if (!$size) {
        }
        $this->addClass('progress-bar');
        if ($type) {
            $this->addClass($type);
        }
    }

    /**
     * @return $this
     */
    function removeStyle() {
        $this->removeClass([
                'progress-bar-success',
                'progress-bar-warning',
                'progress-bar-danger',
                'progress-bar-transparent'
            ]);
        return $this;
    }

    /**
     * @return $this
     */
    function asSuccess() {
        $this->removeStyle()->addClass('progress-bar-success');
        return $this;
    }

    /**
     * @return $this
     */
    function asTransparent() {
        $this->removeStyle()->addClass('progress-bar-transparent');
        return $this;
    }

    /**
     * @return $this
     */
    function asWarning() {
        $this->removeStyle()->addClass('progress-bar-warning');
        return $this;
    }

    /**
     * @return $this
     */
    function asInfo() {
        $this->removeStyle()->addClass('progress-bar-info');
        return $this;
    }

    /**
     * @return $this
     */
    function asDanger() {
        $this->removeStyle()->addClass('progress-bar-danger');
        return $this;
    }

    /**
     * @return $this
     */
    function asDefault() {
        $this->removeStyle();
        return $this;
    }

}