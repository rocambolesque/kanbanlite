<?php

class KanbanLite_Model_StatusMapper extends KanbanLite_Model_Mapper
{
    public function __construct()
    {
        $this->dbTableClassName = 'KanbanLite_Model_DbTable_Status';
    }

    public function find($id, KanbanLite_Model_Status $status)
    {
        $result = $this->getDbTable()->find($id);
        if (count($result) == 0) {
            $status = null;
            return;
        }
        $row = $result->current();
        $status->setId($row->id)
            ->setLabel($row->label);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new KanbanLite_Model_Status();
            $entry->setId($row->id)
                ->setLabel($row->label);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function save(KanbanLite_Model_Status $status)
    {
        $data = array(
            'label' => $status->getLabel(),
        );

        if (($id = $status->getId()) === null) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
}
