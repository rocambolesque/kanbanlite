<?php

abstract class KanbanLite_Model_Model
{
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (($name == 'mapper') || !method_exists($this, $method)) {
            throw new Exception('Invalid card property');
        }

        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (($name == 'mapper') || !method_exists($this, $method)) {
            throw new Exception('Invalid card property');
        }

        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function toJson()
    {
        return Zend_Json::encode(get_object_vars($this));
    }
}
