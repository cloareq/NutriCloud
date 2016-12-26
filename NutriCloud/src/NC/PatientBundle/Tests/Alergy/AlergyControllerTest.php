<?php

namespace NC\PatientBundle\Tests\Controller;

require  __DIR__ . '/../../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class AlergyControllerTest extends WebTestCase
{
	// GET
    public function testAlergy1(){
        $this->loadFixtures(array("NC\\PatientBundle\\Tests\\LoadFixture"));
        // pro get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/allergy/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testAlergy2(){
        // patient get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/allergy/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    // POST 
    public function testAlergy3(){
        // pro create alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/allergy/create/1', array('name' => 'chat'));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testAlergy4(){
        // patient create alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/allergy/create/1', array('name' => 'chat'));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testAlergy5(){
        // pro create alergy bad id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/allergy/create/10', array('name' => 'chat'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testAlergy6(){
        // pro create alergy other patient id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/allergy/create/2', array('name' => 'chat'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 403);
    }

    // GET
    public function testAlergy7(){
        // pro get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/allergy/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","name": "chat"},{"id": "2","name": "chat"}]');
    }

    public function testAlergy8(){
        // patient get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/allergy/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","name": "chat"},{"id": "2","name": "chat"}]');
    }

	//PUT 
	public function testAlergy9(){
        // pro update alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Put', '/pro/allergy/update/1', array('name' => 'chien'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testAlergy10(){
        // patient update alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Put', '/patient/allergy/update/2', array('name' => 'chien'));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    // GET
    public function testAlergy11(){
        // pro get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/allergy/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","name": "chien"},{"id": "2","name": "chien"}]');
    }

    public function testAlergy12(){
        // patient get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/allergy/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","name": "chien"},{"id": "2","name": "chien"}]');
    }

	//DELETE 
	public function testAlergy13(){
        // pro delete alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Delete', '/pro/allergy/delete/1');
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testAlergy14(){
        // patient delete alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Delete', '/patient/allergy/delete/2');
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

	// GET
    public function testAlergy15(){
        // pro get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/allergy/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testAlergy16(){
        // patient get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/allergy/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

}