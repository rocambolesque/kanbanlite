<?php

abstract class KanbanLite_Controller_Action extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        $models = $this->mapper->fetchAll();
        if (count($models) == 0) {
            throw new Zend_Controller_Action_Exception('No ' . $this->pluralElementName, 404);
        }

        $result = array();
        foreach ($models as $model) {
            $result[] = Zend_Json::encode($model);
        }

        echo '{"' . $this->pluralElementName. '": [' . implode($result, ',') . ']}';
    }

    public function getAction()
    {
        $model = new $this->model();
        $this->mapper->find($this->getParam('id'), $model);
        if (!$model) {
            throw new Zend_Controller_Action_Exception($this->elementName.' not found', 404);
        }

        echo '{"' . $this->elementName. '": ' . Zend_Json::encode($model) . '}';
    }

    protected function save()
    {
        try {
            $model = new $this->model($this->params);
            $id = $this->mapper->save($model);
            if (!$id) {
                throw new Zend_Controller_Action_Exception('Error saving element', 500);
            }
            echo Zend_Json::encode(array('id' => $id));
        } catch (\Exception $e) {
            throw new Zend_Controller_Action_Exception('Error saving element: '.$e->getMessage(), 500);
        }
    }
}
