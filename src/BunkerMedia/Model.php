<?php
namespace BunkerMedia;

use Illuminate\Support\Arr;
use \Venturecraft\Revisionable\Revisionable;

class Model extends \Eloquent
{
    use \Laravelrus\LocalizedCarbon\Traits\LocalizedEloquentTrait;

    function getChangedAttributes()
    {
        $ret = [];
        foreach ($this->getOriginal() as $k => $v) {
            $new = Arr::get($this->getAttributes(), $k);
            if ($v != $new) {
                $ret[$k] = [
                    'new' => $new,
                    'old' => $v,
                ];
            }
        }
        return $ret;
    }
}