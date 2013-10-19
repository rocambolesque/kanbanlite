<?php

class CardController extends KanbanLite_Controller_Action
{
    public function init()
    {
        $this->elementName = 'card';
        $this->pluralElementName = $this->elementName.'s';
        $this->mapper = new KanbanLite_Model_CardMapper();
        $this->model = 'KanbanLite_Model_Card';
        parent::init();
    }

    public function indexAction()
    {
        $mongoClient = new MongoClient();
        $mongoDb = $mongoClient->kanbanlite;

        $params = $this->getAllParams();
        if (isset($params['status'])) {
            $models = $mongoDb->cards->find(array('status' => $params['status']));
        } else {
            $models = $mongoDb->cards->find();
        }
        $models->limit(10);

        if ($models->count() == 0) {
            throw new Zend_Controller_Action_Exception('No ' . $this->pluralElementName, 404);
        }

        $result = array();
        foreach ($models as $model) {
            unset($model['_id']);
            $result[] = Zend_Json::encode($model);
        }

        echo '{"' . $this->pluralElementName. '": [' . implode($result, ',') . ']}';
    }

    public function getAction()
    {
        $mongoClient = new MongoClient();
        $mongoDb = $mongoClient->kanbanlite;
        $model = $mongoDb->cards->findOne(array('id' => $this->getParam('id')));

        if (!$model) {
            throw new Zend_Controller_Action_Exception($this->elementName.' not found', 404);
        }

        unset($model['_id']);

        echo '{"' . $this->elementName. '": ' . Zend_Json::encode($model) . '}';
    }

    public function postAction()
    {
        $this->params = $this->_request->getPost($this->elementName);
        // TODO: transaction
        $this->save();
        $this->saveCardStatusOwner();
        $this->updateIndex();
        echo Zend_Json::encode(array('id' => $this->id));
    }

    public function putAction()
    {
        parse_str($this->_request->getRawBody(), $params);
        $this->params = $params['card'];
        $this->params['id'] = $this->_request->getParam('id');
        // TODO: transaction
        $this->save();
        $this->saveCardStatusOwner();
        $this->updateIndex();
        echo Zend_Json::encode(array('id' => $this->id));
    }

    public function deleteAction()
    {
        throw new Zend_Controller_Action_Exception('Not implemented', 500);
    }

    protected function save()
    {
        try {
            $model = new $this->model($this->params);
            $id = $this->mapper->save($model);
            if (!$id) {
                throw new Zend_Controller_Action_Exception('Error saving element', 500);
            }
            $this->id = $id;
        } catch (\Exception $e) {
            throw new Zend_Controller_Action_Exception('Error saving element: '.$e->getMessage(), 500);
        }
    }

    protected function saveCardStatusOwner()
    {
        try {
            $params = array(
                'cardId' => $this->id,
                'ownerId' => $this->getOwner()->getId(),
                'statusId' => $this->getStatus()->getId(),
            );
            $mapper = new KanbanLite_Model_CardStatusOwnerMapper();
            $model = new KanbanLite_Model_CardStatusOwner($params);
            $id = $mapper->save($model);
            if (!$id) {
                throw new Zend_Controller_Action_Exception('Error saving element', 500);
            }
        } catch (\Exception $e) {
            throw new Zend_Controller_Action_Exception('Error saving element: '.$e->getMessage(), 500);
        }
    }

    private function getStatus()
    {
        $mapper = new KanbanLite_Model_StatusMapper();
        $status = new KanbanLite_Model_Status();
        $mapper->find($this->params['status'], $status);
        if (!$status) {
            throw new Zend_Controller_Action_Exception('Invalid status', 400);
        }
        return $status;
    }

    private function getOwner()
    {
        $mapper = new KanbanLite_Model_OwnerMapper();
        $owner = new KanbanLite_Model_Owner();
        $mapper->find($this->params['owner'], $owner);
        if (!$owner) {
            throw new Zend_Controller_Action_Exception('Invalid status', 400);
        }
        return $owner;
    }

    private function updateIndex()
    {
        $dbh = new PDO('mysql:dbname=kanbanlite;host=127.0.0.1', 'root', 'root'); 
        $stmt = $dbh->prepare('
           SELECT
           c.id,
           c.title,
           c.description,
           cso.created_at,
           cso.owner_id AS owner,
           cso.status_id AS status
           FROM card c
           INNER JOIN card_status_owner cso ON c.id = cso.card_id
           WHERE cso.id IN (
              SELECT MAX(cso.id) FROM card_status_owner cso GROUP BY cso.card_id
           )
           AND c.id = :id
        ');

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $mongoClient = new MongoClient();
        $mongoDb = $mongoClient->kanbanlite;
        $collection = $mongoDb->cards;

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // mandatory int to string casting
        $collection->update(array("id" => (string) $this->id), array('$set' => $row));
    }

    public function searchAction()
    {
        $from = $this->getParam('created_at_from');
        $to = $this->getParam('created_at_to');
        $criteria = array();
        if ($from) {
            $criteria['created_at']['$gte'] = $from;
        }
        if ($to) {
            $criteria['created_at']['$lte'] = $to;
        }

        $mongoClient = new MongoClient();
        $mongoDb = $mongoClient->kanbanlite;
        $models = $mongoDb->cards->find($criteria);
        $models->limit(100);

        $result = array();
        if (count($models) > 0) {
            foreach ($models as $model) {
                unset($model['_id']);
                $result[] = Zend_Json::encode($model);
            }
        }

        // FIXME why do I have to setHeader here and not anywhere else?
        $this->getResponse()
            ->setHeader('Content-Type', 'application/json');

        echo '{"' . $this->pluralElementName. '": [' . implode($result, ',') . ']}';
    }

}
