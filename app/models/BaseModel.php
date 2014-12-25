<?php
/**
 * Created by PhpStorm.
 * User: cyrusmith
 * Date: 25.12.2014
 * Time: 19:50
 */

/**
 * Adds single table inheritance feature
 * Code taken from http://www.colorfultyping.com/single-table-inheritance-in-laravel-4/
 */
class BaseModel extends Eloquent
{

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        if ($this->useSti()) {
            $this->setAttribute($this->stiClassField, get_class($this));
        }
    }

    private function useSti()
    {
        return ($this->stiClassField && $this->stiBaseClass);
    }

    public function newQuery($excludeDeleted = true)
    {
        $builder = parent::newQuery($excludeDeleted);
        // If I am using STI, and I am not the base class,
        // then filter on the class name.
        if ($this->useSti() && get_class(new $this->stiBaseClass) !== get_class($this)) {
            $builder->where($this->stiClassField, "=", get_class($this));
        }
        return $builder;
    }

    public function newFromBuilder($attributes = array())
    {
        if ($this->useSti() && $attributes->{$this->stiClassField}) {
            $class = $attributes->{$this->stiClassField};
            $instance = new $class;
            $instance->exists = true;
            $instance->setRawAttributes((array)$attributes, true);
            return $instance;
        } else {
            return parent::newFromBuilder($attributes);
        }
    }
}