<?php
namespace BunkerMedia\Form;

use Acme\User\User;
use BunkerMedia\Form\FormInput;
use Illuminate\Support\Arr;

class Form {
    /**
     * @var \HTML_QuickForm2
     */
    protected $quickform;
    protected $meta;
    protected $model;
    protected $structure    = [];
    protected $use_boostrap = true;
    protected $horizontal   = false;

    /**
     * @param $id
     * @param string $method
     * @param null $attributes
     * @param bool $trackSubmit
     * @return Form
     */
    static function factory($id, $method = 'post', $attributes = null, $trackSubmit = true) {
        return new Form ($id, $method, $attributes, $trackSubmit);
    }

    function __construct($id, $method = 'post', $attributes = null, $trackSubmit = true) {
        $this->quickform = new \HTML_QuickForm2($id, $method, $attributes, $trackSubmit);
    }

    /**
     * @return \HTML_QuickForm2
     */
    function getQuickform() {
        return $this->quickform;
    }

    /**
     * @param $model
     * @return $this
     */
    function loadModel($model) {
        $this->model = $model;
        \Schema::getColumnListing('')
        $this->initValues($model->toArray());
        return $this;
    }

    /**
     * @param $model
     * @return $this
     */
    function setAction() {
        $this->quickform->setAttribute('action', '/');
        return $this;
    }

    /**
     * @param $model
     * @return $this
     */
    function asHorizontal() {
        $this->quickform->addClass('form-horizontal');
        $this->horizontal = true;
        return $this;
    }


    function initValues($values = null) {
        if ($values) {
            $this->quickform->addDataSource(new \HTML_QuickForm2_DataSource_Array($values));
        }
    }

    function setErrors($errors) {
        $elements = $this->quickform->getElements();
        foreach ($elements as $el) {
            $error = Arr::get($errors, $el->getName());
            if ($error) {
                $el->setError(implode(' ', $error));
            }
        }
    }

    /**
     * @param FormInput $element
     * @param $name
     * @param $value
     * @return $this
     */
    function setMetaElement(FormInput $element, $name, $value) {
        $id                     = $element->getElement()->getId();
        $this->meta[$id][$name] = $value;
        return $this;
    }

    /**
     * @return FormInput
     */
    function addCsrf() {
        $token = md5(__LINE__ . __FILE__ . microtime(true));
        return $this->addHidden('_token', $token);
    }

    /**
     * @param $name
     * @param $value
     * @return FormInput
     */
    function addHidden($name, $value) {
        $qf = $this->quickform->addHidden($name);
        $el = FormInput::factory($qf, $this);
        $el->addValue($value);
        return $el;
    }

    /**
     * @param $name
     * @return FormInput
     */
    function addText($name) {
        $qf = $this->quickform->addText($name);
        $el = FormInput::factory($qf, $this);
        if ($this->use_boostrap) {
            $el->addClass('form-control');
        }
        return $el;
    }

    /**
     * @param $label
     * @return $this
     */
    function addFieldset($label) {
        return $this->quickform->addElement('fieldset')->setLabel($label);
    }

    /**
     * @param $label
     * @return $this
     */
    function addSubmit($label, $name = null) {
        $el = $this->quickform->addElement('submit', $name, ['value' => $label]);
        if ($this->use_boostrap) {
            $el->addClass('btn btn-success');
        }
        return $el;
    }

    /**
     * @param string $tpl
     * @return string
     * @throws string
     */
    function render($tpl = '!.quickform') {
        $renderer = \HTML_QuickForm2_Renderer::factory('array');
        $this->quickform->render($renderer);
        return \Twig::render($tpl, [
            'form' => $renderer->toArray(),
            'meta' => $this->meta + ['horizontal' => $this->horizontal],
        ]);
    }

    function validate() {
        return $this->quickform->validate();
    }

    function __toString() {
        return $this->render();
    }

}