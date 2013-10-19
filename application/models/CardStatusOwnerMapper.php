<?php

class KanbanLite_Model_CardStatusOwnerMapper extends KanbanLite_Model_Mapper
{
    public function __construct()
    {
        $this->dbTableClassName = 'KanbanLite_Model_DbTable_CardStatusOwner';
    }
    
    public function find($id, KanbanLite_Model_CardStatusOwner $cardStatusOwner)
    {
        $result = $this->getDbTable()->find($id);
        if (count($result) == 0) {
            $cardStatusOwner = null;
            return;
        }
        $row = $result->current();
        $cardStatusOwner->setId($row->id)
            ->setCardId($row->cardId)
            ->setOwnerId($row->ownerId)
            ->setStatusId($row->statusId)
            ->setCreatedAt($row->createdAt);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new KanbanLite_Model_CardStatusOwner();
            $entry->setId($row->id)
                ->setCardId($row->cardId)
                ->setOwnerId($row->ownerId)
                ->setStatusId($row->statusId)
                ->setCreatedAt($row->createdAt);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function save(KanbanLite_Model_CardStatusOwner $cardStatusOwner)
    {
        $data = array(
            'card_id'    => $cardStatusOwner->getCardId(),
            'owner_id'   => $cardStatusOwner->getOwnerId(),
            'status_id'  => $cardStatusOwner->getStatusId(),
        );

        // no update
        $record = $this->findOneBy($data);
        if ($record) {
            return $record->getId();
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->getDbTable()->insert($data);
    }

    public function findMostRecentByCardId($id, KanbanLite_Model_CardStatusOwner $cardStatusOwner)
    {
        $table = $this->getDbTable();
        $select = $table->select()
           ->where('card_id = ?', $id)
           ->order(array('created_at DESC', 'id DESC')) // additional order on id if created_at dates are equal
           ->limit(1, 0);
        $rows = $table->fetchAll($select);

        if (count($rows) == 0) {
            $cardStatusOwner = null;
            return;
        }

        $row = $rows[0];
        $cardStatusOwner->setId($row->id)
            ->setCardId($row->card_id)
            ->setOwnerId($row->owner_id)
            ->setStatusId($row->status_id)
            ->setCreatedAt($row->created_at);
    }

    // TODO move to abstract mapper
    public function findOneBy($fields = array())
    {
        if (empty($fields)) {
            throw new \Exception('findOneBy requires at least one field');
        }
        $table = $this->getDbTable();
        $select = $table->select();

        foreach ($fields as $field => $value) {
            $select->where($field.' = ?', $value);
        }

        $rows = $table->fetchAll($select);

        if (count($rows) == 0) {
            return null;
        }

        $row = $rows[0];
        $entry = new KanbanLite_Model_CardStatusOwner();
        $entry->setId($row->id)
            ->setCardId($row->card_id)
            ->setOwnerId($row->owner_id)
            ->setStatusId($row->status_id)
            ->setCreatedAt($row->created_at);
        return $entry;
    }
}
