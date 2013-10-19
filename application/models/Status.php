<?php

class KanbanLite_Model_Status extends KanbanLite_Model_Model
{
    protected $id;
    protected $label;

    public function setLabel($label)
    {
        $this->label = (string) $label;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

}
