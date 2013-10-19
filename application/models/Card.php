<?php

class KanbanLite_Model_Card extends KanbanLite_Model_Model
{
    protected $id;
    protected $title;
    protected $description;

    public function setTitle($title)
    {
        $this->title = (string) $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = (string) $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function toJson()
    {
        $card = get_object_vars($this);

        $cardStatusOwner = new KanbanLite_Model_CardStatusOwner();
        $mapper = new KanbanLite_Model_CardStatusOwnerMapper();
        $mapper->findMostRecentByCardId($this->id, $cardStatusOwner);

        $card['status'] = $cardStatusOwner->getStatusId();
        $card['owner'] = $cardStatusOwner->getOwnerId();

        return Zend_Json::encode($card);
    }
}
