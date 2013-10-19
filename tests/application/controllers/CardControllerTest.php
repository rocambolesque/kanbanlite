<?php

class CardControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->bootstrap = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        parent::setUp();
    }

    public function getRandom($model, $plural, $idNotIn = array())
    {
        $this->resetRequest();
        $this->resetResponse();

        $this->request->setMethod('GET');
        $this->dispatch('/'.$model);
        $response = json_decode($this->getResponse()->getBody(), true);
        $this->assertNotNull($response[$plural]);

        $max = count($response[$plural])-1;
        $result = $response[$plural][0];
        if (!empty($idNotIn) && $max >= 1) {
            do {
                $index = rand(0, $max);
            }
            while(in_array($response[$plural][$index]['id'], $idNotIn));
            $result = $response[$plural][$index];
        }

        $this->resetRequest();
        $this->resetResponse();

        return $result;
    }

    public function testPost()
    {
        $status = $this->getRandom('status', 'statuses');
        $owner = $this->getRandom('owner', 'owners');

        $title = 'Buy groceries '.date('d/m H:i:s');
        $description = 'Carrots '.date('d/m H:i:s');

        // POST a card
        $this->request->setMethod('POST')
            ->setPost(array(
                'card' => array(
                    'title'       => $title,
                    'description' => $description,
                    'status'      => $status['id'],
                    'owner'       => $owner['id'],
                )
            )
        );

        $this->dispatch('/card');
        $response = json_decode($this->getResponse()->getBody(), true);
        $this->assertNotNull($response);

        $this->resetRequest();
        $this->resetResponse();

        // GET the POSTed card
        $this->request->setMethod('GET');
        $this->dispatch('/card/'.$response['id']);
        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertEquals($title, $response['card']['title']);
        $this->assertEquals($description, $response['card']['description']);
        $this->assertEquals($status['id'], $response['card']['status']);
        $this->assertEquals($owner['id'], $response['card']['owner']);
    }

    public function testIndex()
    {
        $this->request->setMethod('GET');
        $this->dispatch('/card');
        $response = json_decode($this->getResponse()->getBody(), true);
        $this->assertNotNull($response['cards'][0]);
    }

    public function testPut()
    {
        $status = $this->getRandom('status', 'statuses');
        $owner = $this->getRandom('owner', 'owners');

        $title = 'Buy groceries '.date('d/m H:i:s');
        $description = 'Carrots '.date('d/m H:i:s');

        // POST a card
        $this->request->setMethod('POST')
            ->setPost(array(
                'card' => array(
                    'title'       => $title,
                    'description' => $description,
                    'status'      => $status['id'],
                    'owner'       => $owner['id'],
                )
            )
        );

        $this->dispatch('/card');
        $response = json_decode($this->getResponse()->getBody(), true);
        $this->assertNotNull($response);

        $this->resetRequest();
        $this->resetResponse();

        // GET the POSTed card
        $this->request->setMethod('GET');
        $this->dispatch('/card/'.$response['id']);
        $response = json_decode($this->getResponse()->getBody(), true);
        $card = $response['card'];

        $title = 'PUT a card'.date('Y-m-d H:i:s');
        $description = 'PUT a card description '.date('Y-m-d H:i:s');
        $status = $this->getRandom('status', 'statuses', array($card['status']));
        $owner = $this->getRandom('owner', 'owners', array($card['owner']));

        // PUT the card
        $this->request->setMethod('PUT')
            ->setRawBody(http_build_query(array(
                'card' => array(
                    'title'       => $title,
                    'description' => $description,
                    'status'      => $status['id'],
                    'owner'       => $owner['id'],
                )
            ))
        );

        $this->dispatch('/card/'.$card['id']);
        $response = json_decode($this->getResponse()->getBody(), true);
        $this->assertNotNull($response);

        $this->resetRequest();
        $this->resetResponse();

        // GET the PUTed card
        $this->request->setMethod('GET');
        $this->dispatch('/card/'.$card['id']);
        $response = json_decode($this->getResponse()->getBody(), true);

        $this->assertEquals($title, $response['card']['title']);
        $this->assertEquals($description, $response['card']['description']);
        $this->assertEquals($status['id'], $response['card']['status']);
        $this->assertEquals($owner['id'], $response['card']['owner']);
    }
}
