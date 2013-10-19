<?php

class KanbanLite_Model_CardStatusOwner extends KanbanLite_Model_Model
{
    protected $id;
    protected $cardId;
    protected $statusId;
    protected $ownerId;
    protected $createdAt;

    public function setCardId($cardId)
    {
        $this->cardId = (string) $cardId;
        return $this;
    }

    public function getCardId()
    {
        return $this->cardId;
    }

    public function setStatusId($statusId)
    {
        $this->statusId = (string) $statusId;
        return $this;
    }

    public function getStatusId()
    {
        return $this->statusId;
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId = (int) $ownerId;
        return $this;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = (int) $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
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
