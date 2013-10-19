<?php

class KanbanLite_Model_OwnerMapper extends KanbanLite_Model_Mapper
{
    public function __construct()
    {
        $this->dbTableClassName = 'KanbanLite_Model_DbTable_Owner';
    }
    
    public function find($id, KanbanLite_Model_Owner $owner)
    {
        $result = $this->getDbTable()->find($id);
        if (count($result) == 0) {
            $owner = null;
            return;
        }
        $row = $result->current();
        $owner->setId($row->id)
            ->setName($row->name);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new KanbanLite_Model_Owner();
            $entry->setId($row->id)
                ->setName($row->name);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function save(KanbanLite_Model_Owner $owner)
    {
        $data = array(
            'name' => $owner->getName(),
        );

        if (($id = $owner->getId()) === null) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            return $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
}
