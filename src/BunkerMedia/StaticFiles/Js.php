<?php
namespace BunkerMedia\StaticFiles;

use \Illuminate\Support\Arr;
use \Config;

class Js extends File {
    /* внешние подключаемые скрипты */
    public $js_external = [];
    /* инлайн скрипты */
    public $js_inline = [];
    /* скрипты, которые должны быть выполнены при загрузке странице */
    public $js_onload = [];

    function __construct() {
        $this->needJquery();
    }

    function clearAll() {
        return $this->clearExternal()->clearInline()->clearOnload();
    }

    function clearExternal() {
        $this->js_external = [];
        return $this;
    }

    function clearInline() {
        $this->js_inline = [];
        return $this;
    }

    function clearOnload() {
        $this->js_onload = [];
        return $this;
    }

    /**
     * @return Js
     */

    static function instance() {
        static $js;
        if (!isset($js)) {
            $js = new Js();
        }
        return $js;
    }

    /**
     * Подключение внешнего скрипта, реально лежащего в корне сайта
     * @param string $js
     */
    function add($js, $condition = null, $no_build = false) {
        if (!$js)
            return $this;
        //если начинается с / значит надо в урл добавить хост
        if (mb_strpos($js, '/') === 0) {
            $js = Config::get('staticfiles.host') . $js;
        }
        $this->js_external[$js] = [
            'condition' => $condition,
            'no_build'  => $no_build,
        ];
        return $this;
    }


    /**
     * Добавление куска инлайн джаваскрипта
     * @param <type> $js
     * @param mixed $id - уникальный флаг куска кода, чтобы можно
     * было добавлять в цикле и не бояться дублей
     */
    function addInline($js, $id = null) {
        if ($id) {
            $this->js_inline[$id] = $js;
        }
        else {
            $this->js_inline[] = $js;
        }
        return $this;
    }

    /**
     * Добавление кода, который должен выполниться при загрузке страницы
     * @param string $js
     * @param mixed $id - уникальный флаг куска кода, чтобы можно
     * было добавлять в цикле и не бояться дублей
     */
    function addOnload($js, $id = null) {
        if ($id) {
            $this->js_onload[$id] = $js;
        }
        else {
            $this->js_onload[] = $js;
        }
        return $this;
    }

    /**
     * Использовать во View для вставки вызова всех скриптов
     * @return string
     */
    function __toString() {
        return trim($this->get_external() . PHP_EOL . $this->getInline() . PHP_EOL . $this->get_onload());
    }

    function getLink($js, $condition = null) {
        return ($condition ? '<!--[' . $condition . ']>' : '') . '<script type="text/javascript" ' . "" . 'src="' . $js . '"></script>' . ($condition ? '<![endif]-->' : '');
    }

    /**
     * Только внешние скрипты
     * @return string
     */
    function getExternal() {
        if (!count($this->js_external))
            return '';
        //если не надо собирать все в один билд-файл
        if (!Config::get('staticfiles.js.external.build')) {
            $js_code = '';
            foreach ($this->js_external as $js => $_js) {
                $condition = Arr::get($_js, 'condition');
                //если надо подключать все по отдельности
                $js_code .= $this->getLink($js, $condition) . "\n";
            }
            return $js_code;
        }
        else {
            $build    = [];
            $no_build = [];
            $js_code  = '';
            foreach ($this->js_external as $js => $_js) {
                $condition = Arr::get($_js, 'condition');
                if (Arr::get($_js, 'no_build')) {
                    $no_build[$condition][] = $js;
                }
                else {
                    $build[$condition][] = $js;
                }
            }
            foreach ($no_build as $condition => $js) {
                $condition = Arr::get($_js, 'condition');
                foreach ($js as $url) {
                    $js_code .= $this->getLink($url, $condition) . "<!-- no build -->" . PHP_EOL;
                }
            }
            foreach ($build as $condition => $js) {
                $build_name = $this->makeFileName($this->js_external, 'js/external' . ($condition ? '/' . $condition : ''), 'js');
                $build_file = $this->buildFile($build_name);
                if (!file_exists($build_file)) {
                    //соберем билд в первый раз
                    $build = [];
                    foreach ($js as $url) {
                        $_js     = $this->getSource($url);
                        $_js     = $this->prepare($_js, Config::get('staticfiles.js.external.min'));
                        $build[] = $_js;
                    }
                    //если требуется собирать инлайн скрипты в один внешний файл
                    $this->requireBuild($build_name, implode("\n", $build));
                }
                $js_code .= $this->getLink($this->buildUrl($build_name), $condition) . PHP_EOL;
            }
            //$build_name = $this->makeFileName($this->js_inline, 'js/onload', 'js');
            return $js_code;
        }
    }

    function requireBuild($build_name, $source) {
        $build_file = $this->buildFile($build_name);
        if (!file_exists($build_file)) {
            if (!file_exists(dirname($build_file))) {
                mkdir(dirname($build_file), 0777, true);
            }
            $this->save($build_file, trim($source));
        }
    }

    function prepare($source, $need_min) {
        if ($need_min) {
            include_once \Kohana::find_file('vendor', 'jsmin');
            $source = \JSMin::minify($source);
        }
        return trim($source);
    }

    /**
     * Только инлайн
     * @return <type>
     */
    function get_inline($as_html = true) {
        if (!$as_html) {
            return $this->js_inline;
        }
        if (!count($this->js_inline))
            return '';
        $js_code = '';
        foreach ($this->js_inline as $js) {
            $js_code .= $this->prepare($js, $this->get_need_min_inline());
        }
        $js_code = trim($this->prepare($js_code, $this->get_need_min_inline()));
        if (!$js_code)
            return '';
        if (!$this->get_need_build_inline()) {
            return '<script type="text/javascript">
' . trim($js_code) . '
</script>';
        }
        //если требуется собирать инлайн скрипты в один внешний файл
        $build_name = $this->makeFileName($this->js_inline, 'js/inline', 'js');
        $this->require_build($build_name, $js);
        return $this->get_link($this->buildUrl($build_name)) . PHP_EOL;
    }


    function needJquery() {
        $jquery = Config::get('staticfiles.jquery');
        return $this->add($jquery);
    }

    /**
     * Только онлоад
     * @return <type>
     */
    function getOnload($as_html = true) {
        if (!$as_html) {
            return $this->js_onload;
        }
        if (!count($this->js_onload))
            return '';
        $js = '';
        foreach ($this->js_onload as $k => $_js) {
            $js .= trim($_js) . PHP_EOL;
        }
        $js = 'jQuery(document).ready(function(){' . PHP_EOL . $js . '});';
        $js = $this->prepare($js, Config::get('staticfiles.onload.min'));
        if (!Config::get('staticfiles.onload.build')) {
            $ret = '<script>' . PHP_EOL . $js . PHP_EOL . '</script>';
            return $ret;
        }
        //если требуется собирать инлайн скрипты в один внешний файл
        $build_name = $this->makeFileName($this->js_onload, 'js/onload', 'js');
        $this->requireBuild($build_name, $js);
        return $this->getLink($this->buildUrl($build_name)) . PHP_EOL;
    }

}