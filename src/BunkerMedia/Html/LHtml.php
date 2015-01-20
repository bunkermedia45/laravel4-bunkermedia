<?php

namespace BunkerMedia\Html;

class LHtml extends \HTML_Common2
{
    protected $_content    = '';
    protected $_type       = '';
    protected $_has_closed = true;

    function setValue($value)
    {
        $this->setAttribute('value', $value);
        return $this;
    }

    function setId($value)
    {
        $this->setAttribute('id', __($value));
        return $this;
    }

    function setTitle($value)
    {
        $this->setAttribute('title', $value);
        return $this;
    }

    function getTitle()
    {
        return $this->getAttribute('title');
    }

    function setContent($content)
    {
        $this->_content = $content;
        return $this;
    }

    function getType()
    {
        return $this->_type;
    }

    function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    function setHasClosed($val)
    {
        $this->_has_closed = (bool)$val;
        return $this;
    }

    function __toString()
    {
        if ($this->_has_closed) {
            return sprintf('<%s%s>%s</%s>', $this->_type, $this->getAttributes(true), $this->getContent(), $this->_type);
        } else {
            return sprintf('<%s%s />', $this->_type, $this->getAttributes(true));
        }
    }

    function getContent()
    {
        return $this->_content;
    }

}