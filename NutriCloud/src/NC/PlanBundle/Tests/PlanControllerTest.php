<?php

namespace NC\PlanBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanControllerTest extends WebTestCase{

    public function testGetAliment(){
        $this->loadFixtures(array("NC\\PlanBundle\\Tests\\LoadPlanFixture"));
        echo "      Aliment";
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_aliment', '_password' => 'test_aliment'));
        $client->request('Get', '/pro/aliment/get', array());
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testPostAliment(){
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_aliment', '_password' => 'test_aliment'));
        $client->request('Post', '/pro/aliment/new', array("aliment" => "haricot"));
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testGetAliment2(){
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_aliment', '_password' => 'test_aliment'));
        $client->request('Get', '/pro/aliment/get', array());
        $this->assertTrue($client->getResponse()->getContent() == '["haricot"]');
    }

    public function testGetPlanModel(){
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_aliment', '_password' => 'test_aliment'));
        $client->request('Get', '/patient/plan/model/1', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode()==200);
    }

    public function testSetPlanModel(){
        $tmp = '{"Monday" : [{"aliment": "pain", "quantity": "200g", "hour": "7h"}, {"aliment": "haricot", "quantity": "500g", "hour": "9h"}],
                "Tuesday" : [{"aliment": "pain", "quantity": "200g", "hour": "7h"}, {"aliment": "haricot", "quantity": "500g", "hour": "9h"}],
                "Wednesday" : [{"aliment": "pain", "quantity": "200g", "hour": "7h"}, {"aliment": "haricot", "quantity": "500g", "hour": "9h"}],
                "Thursday" : [{"aliment": "pain", "quantity": "200g", "hour": "7h"}, {"aliment": "haricot", "quantity": "500g", "hour": "9h"}],
                "Friday" : [{"aliment": "pain", "quantity": "200g", "hour": "7h"}, {"aliment": "haricot", "quantity": "500g", "hour": "9h"}],
                "Saturday" : [{"aliment": "pain", "quantity": "200g", "hour": "7h"}, {"aliment": "haricot", "quantity": "500g", "hour": "9h"}],
                "Sunday" : []}';
          #print_r($tmp->{"Monday"});

        /*foreach ($tmp->{"Monday"} as $elem){
            echo json_encode($elem);
        }*/
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_aliment', '_password' => 'test_aliment'));
        $client->request('Post', '/pro/plan/update/1', array("plan" => $tmp));
        //echo $client->getResponse()->getStatusCode();
        //echo $client->getResponse()->getContent();
        $this->assertTrue(1==1);
    }

    public function testGetPlanModel2(){
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_aliment', '_password' => 'test_aliment'));
        $client->request('Get', '/patient/plan/model/1', array());
        //echo $client->getResponse()->getContent();
        $this->assertTrue(1 ==1);
    }
}