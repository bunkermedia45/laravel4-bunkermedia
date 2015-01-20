<?php

namespace BunkerMedia\Html;

class Progress extends \BunkerMedia\Html\Div {

    protected $size       = 0;
    protected $calculated = 0;
    protected $items      = [];
    protected $auto_color = 0;

    function __construct() {
        $this->addClass('progress');
    }

    /**
     * @return Progress
     */
    static function factory() {
        return new Progress();
    }

    /**
     * @return $this
     */
    function setSize($size = null) {
        if (!is_null($size)) {
            $this->size = $size;
        }

        return $this;
    }

    /**
     * @return $this
     */
    function asActive($val = true) {
        if ($val) {
            $this->addClass('active');
        }
        else {
            $this->removeClass('active');
        }

        return $this;
    }

    /**
     * @return $this
     */
    function asStriped($val = true) {
        if ($val) {
            $this->addClass('progress-striped');
        }
        else {
            $this->removeClass('progress-striped');
        }

        return $this;
    }

    /**
     * @return $this
     */
    function asAutoColor($val = 1) {
        $this->auto_color = (int)$val;
        return $this;
    }

    /**
     *
     * @param type $value
     * @param type $type
     *
     * @return ProgressBar
     */
    function &add($value) {
        $this->calculated += $value;
        //var_dump($value);
        $name               = uniqid(microtime(true), true);
        $this->items[$name] = new ProgressBar($value);

        return $this->items[$name];
    }

    function getContent() {
        try {
            $ret       = [];
            $real_size = $this->size ? $this->size : $this->calculated;
            //            print '<pre>';
            //            var_dump($this->items);
            //            var_dump('------------------');
            //            var_dump($real_size);
            //            var_dump('------------------');
            foreach ($this->items as $bar) {
                $style = $bar->getAttribute('style');
                if ($style) {
                    $style .= ';';
                }

                if ($real_size && $this->calculated) {
                    $percent = $bar->size / $real_size * 100;

                    //                    var_dump($real_size);
                    //                    var_dump($this->size);
                    //                    var_dump($this->calculated);
                    //                    var_dump($percent);
                    //                    exit;
                    if ($this->size > 0 and ($bar->size > $this->size)) {
                        $w_percent = 100;
                    }
                    elseif ($this->size < 0 and ($bar->size > $this->size)) {
                        $bar->addClass('pull-right');
                        $w_percent = abs($bar->size / $this->size * 100);
                    }
                    else {
                        if ($this->size) {
                            $w_percent = $bar->size / $this->size * 100;
                        }
                        else {
                            $w_percent = $bar->size ? 100 : 0;
                        }
                    }
                }
                else {
                    $percent   = 0;
                    $w_percent = 0;
                }

                if ($this->auto_color > 0) {
                    switch (true) {
                        case $percent > 66:
                            $bar->asSuccess();
                            break;
                        case $percent > 33:
                            $bar->asWarning();
                            break;
                        default:
                            $bar->asDanger();
                            break;
                    }
                    $title = number_format($percent, 2, '.', '.') . '%';
                    if (!$bar->getContent()) {
                        $bar->setContent($title)->setTitle($title);
                    }

                }
                else if ($this->auto_color < 0) {
                    switch (true) {
                        case $percent > 66:
                            $bar->asDanger();
                            break;
                        case $percent > 33:
                            $bar->asWarning();
                            break;
                        default:
                            $bar->asSuccess();
                            break;
                    }
                    if (!$bar->getContent()) {
                        $title = $bar->setTitle() . ' ' . number_format($percent, 1, '.', '.') . '%';
                        $bar->setContent($title)->setTitle($title);
                    }
                }

                //                var_dump('$real_size: ' . $real_size);
                //                var_dump('$this->calculated: ' . $this->calculated);
                //                var_dump('$this->size: ' . $this->size);
                //                var_dump('$bar->size: ' . $bar->size);
                //                var_dump('$percent: ' . $percent);
                //                var_dump('$w_percent: ' . $w_percent);
                //                var_dump('$bar: ' . $bar);
                //                print '</pre>';
                //                exit;

                $bar->setAttribute('style', $style . 'width:' . number_format($w_percent, 4, '.', '.') . '%');
                $ret[] = $bar;
            }

            return implode('', $ret);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return $this
     */
    function removeStyle() {
        $this->removeClass('progress-success progress-warning progress-danger progress-transparent');

        return $this;
    }

    /**
     * @return $this
     */
    function asSuccess() {
        $this->removeStyle()->addClass('progress-success');

        return $this;
    }

    /**
     * @return $this
     */
    function asTransparent() {
        $this->removeStyle()->addClass('progress-transparent');

        return $this;
    }

    /**
     * @return $this
     */
    function asWarning() {
        $this->removeStyle()->addClass('progress-warning');

        return $this;
    }

    /**
     * @return $this
     */
    function asDanger() {
        $this->removeStyle()->addClass('progress-danger');

        return $this;
    }

    /**
     * @return $this
     */
    function asDefault() {
        $this->removeStyle();

        return $this;
    }

    function __toString() {
        try {
            return sprintf('<%s%s>%s</%s>', $this->_type, $this->getAttributes(true), $this->getContent(), $this->_type);
        } catch (\Exception $e) {
            var_dump($e->getFile());
            var_dump($e->getLine());
            return $e->getMessage();
        }
    }

}
