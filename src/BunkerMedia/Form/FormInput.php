<?php

namespace BunkerMedia\Form;

class FormInput {
    /**
     * @var \HTML_Common2
     */
    protected $element;
    /**
     * @var Form
     */
    protected $form;

    function __construct($element, $form) {
        $this->element = $element;
        $this->form    = $form;
    }

    static function factory($element, $form) {
        return new FormInput($element, $form);
    }


    function getElement() {
        return $this->element;
    }

    function addValue($value) {
        $this->element->setAttribute('value', $value);
    }

    function addClass($value) {
        $this->element->addClass($value);
    }

    function setExample($value) {
        $this->form->setMetaElement($this, 'example', $value);
        return $this;
    }

    function setLabel($value) {
        $this->form->setMetaElement($this, 'label', $value);
        return $this;
    }

    function setRequired($value = 1) {
        $this->form->setMetaElement($this, 'required', $value);
        return $this;
    }

    function setInline($value = 1) {
        $this->form->setMetaElement($this, 'inline', $value);
        return $this;
    }

    function setDesc($value) {
        $this->form->setMetaElement($this, 'desc', $value);
        return $this;
    }

    function setError($value) {
        $this->element->setError($value);
        return $this;
    }

}
