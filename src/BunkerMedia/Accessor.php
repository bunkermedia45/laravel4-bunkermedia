<?php
namespace BunkerMedia;
class Accessor
{
    /**
     * @var \Eloquent
     */

    function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $model
     * @return Accessor
     */
    static function factory($model, $default=null)
    {
        if(!is_object($model)) return $default;
        $class = str_replace('BunkerMedia\Model\\', '', get_class($model));
        if (class_exists('BunkerMedia\Accessor\\' . $class)) {
            $class = 'BunkerMedia\Accessor\\' . $class;
        } else {
            $class = 'BunkerMedia\Accessor';
        }
        return new $class($model);
    }

    function __get($name)
    {
        return $this->get($name);
    }

    function original($name)
    {
        $method = 'get' . studly_case($name) . 'Original';
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
        return $this->get($name);
    }

    function get($name)
    {
        $field = $this->model->{$name};
        if(isset($field) && is_object($field)){
            return Accessor::factory($field);
//            return Accessor::factory($field)->__toString();
        }
        $method = 'get' . studly_case($name) . 'Attribute';
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
        return $this->model->{$name};
    }

    function __toString()
    {
        return $this->model->__toString();
    }

}