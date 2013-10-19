<?php

class StatusController extends KanbanLite_Controller_Action
{
    public function init()
    {
        $this->elementName = 'status';
        $this->pluralElementName = $this->elementName.'es';
        $this->mapper = new KanbanLite_Model_StatusMapper();
        $this->model = 'KanbanLite_Model_Status';
        parent::init();
    }

    public function postAction()
    {
        throw new Zend_Controller_Action_Exception('Not implemented', 500);
    }

    public function putAction()
    {
        throw new Zend_Controller_Action_Exception('Not implemented', 500);
    }

    public function deleteAction()
    {
        throw new Zend_Controller_Action_Exception('Not implemented', 500);
    }
}
