<?php

class KanbanLite_Model_CardMapper extends KanbanLite_Model_Mapper
{
    public function __construct()
    {
        $this->dbTableClassName = 'KanbanLite_Model_DbTable_Card';
    }
    
    public function find($id, KanbanLite_Model_Card $card)
    {
        $result = $this->getDbTable()->find($id);
        if (count($result) == 0) {
            $card = null;
            return;
        }
        $row = $result->current();
        $card->setId($row->id)
            ->setTitle($row->title)
            ->setDescription($row->description);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new KanbanLite_Model_Card();
            $entry->setId($row->id)
                ->setTitle($row->title)
                ->setDescription($row->description);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function save(KanbanLite_Model_Card $card)
    {
        $data = array(
            'title'       => $card->getTitle(),
            'description' => $card->getDescription(),
        );

        if (($id = $card->getId()) === null) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return $id;
        }
    }
}
