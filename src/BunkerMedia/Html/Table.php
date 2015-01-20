<?php
namespace BunkerMedia\Html;

class Table extends LHtml {
    protected $_type = 'table';
    protected $_has_closed = true;

    /**
     * Factory table element
     * @return Table
     */
    static function factory() {
        return new Table();
    }

    protected $rows = array();

    /**
     * Add named row in table
     * @param string $name
     * @return TableRow
     */
    function &addRow($name = null) {
        if (!$name) {
            $name = uniqid(microtime(true), true);
        }
        $this->rows[$name] = new TableRow();
        return $this->rows[$name];
    }

    function insertRow(TableRow $row) {
        $this->rows[uniqid(microtime(true), true)] = $row;
    }

    /**
     * Get named row from table
     * @param string $name
     * @return TableRow
     */
    function &getRow($name) {
        return $this->rows[$name];
    }

    function getContent() {
        return PHP_EOL . "\t<tbody>" . implode(PHP_EOL . "\t", $this->rows) . '</tbody>'.PHP_EOL;
    }

}
