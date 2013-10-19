<?php

class OwnerController extends KanbanLite_Controller_Action
{
    public function init()
    {
        $this->elementName = 'owner';
        $this->pluralElementName = $this->elementName.'s';
        $this->mapper = new KanbanLite_Model_OwnerMapper();
        $this->model = 'KanbanLite_Model_Owner';
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
