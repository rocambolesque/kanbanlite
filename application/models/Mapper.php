<?php

abstract class KanbanLite_Model_Mapper
{
    protected $dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }

        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }

        $this->dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if ($this->dbTable === null) {
            $this->setDbTable($this->dbTableClassName);
        }

        return $this->dbTable;
    }
}
