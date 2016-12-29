<?php

namespace NC\PatientBundle\Tests\Controller;

require  __DIR__ . '/../../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class GoalControllerTest extends WebTestCase
{
	// GET
    public function testGoal1(){
        $this->loadFixtures(array("NC\\PatientBundle\\Tests\\LoadFixture"));
        // pro get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/goal/1');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testGoal2(){
        // patient get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/goal/1');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    // POST 
    public function testGoal3(){
        // pro create alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/goal/create/1', array('percentage' => 10, 'text' => 'faire du sport', 'date' => 'mardi'));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testGoal4(){
        // patient create alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/goal/create/1', array('percentage' => 10, 'text' => 'faire du sport', 'date' => 'mardi'));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }
    public function testGoal5(){
        // pro create alergy bad id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/goal/create/10', array('percentage' => 10, 'text' => 'faire du sport', 'date' => 'mardi'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testGoal6(){
        // pro create alergy other patient id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/goal/create/2', array('percentage' => 10, 'text' => 'faire du sport', 'date' => 'mardi'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 403);
    }

    // GET
    public function testGoal7(){
        // pro get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/goal/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","goal": "faire du sport","percentage": "10","date": "mardi"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","goal": "faire du sport","percentage": "10","date": "mardi"') !== false);
    }

    public function testGoal8(){
        // patient get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/goal/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","goal": "faire du sport","percentage": "10","date": "mardi"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","goal": "faire du sport","percentage": "10","date": "mardi"') !== false);
    }

	//PUT 
	public function testGoal9(){
        // pro update alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Put', '/pro/goal/update/1', array('percentage' => 10, 'text' => 'faire du sport', 'date' => 'lundi'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testGoal10(){
        // patient update alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Put', '/patient/goal/update/2', array('percentage' => 10, 'text' => 'faire du sport', 'date' => 'lundi'));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    // GET
    public function testGoal11(){
        // pro get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/goal/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","goal": "faire du sport","percentage": "10","date": "lundi"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","goal": "faire du sport","percentage": "10","date": "lundi"') !== false);
    }

    public function testGoal12(){
        // patient get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/goal/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","goal": "faire du sport","percentage": "10","date": "lundi"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","goal": "faire du sport","percentage": "10","date": "lundi"') !== false);
    }

	//DELETE 
	public function testGoal13(){
        // pro delete alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Delete', '/pro/goal/delete/1');
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testGoal14(){
        // patient delete alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Delete', '/patient/goal/delete/2');
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

	// GET
    public function testGoal15(){
        // pro get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/goal/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testGoal16(){
        // patient get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/goal/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

}