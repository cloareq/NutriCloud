<?php

namespace NC\PatientBundle\Tests\Controller;

require  __DIR__ . '/../../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class MedicationControllerTest extends WebTestCase
{
	// GET
    public function testMedication1(){
        $this->loadFixtures(array("NC\\PatientBundle\\Tests\\LoadFixture"));
        // pro get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/medication/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }
    public function testMedication2(){
        // patient get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/medication/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    // POST 
    public function testMedication3(){
        // pro create alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/medication/create/1', array('name' => 'doliprane', 'note' => 'mal de tete', 'is_active' => True));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testMedication4(){
        // patient create alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/medication/create/1', array('name' => 'doliprane', 'note' => 'mal de tete', 'is_active' => True));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testMedication5(){
        // pro create alergy bad id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/medication/create/10', array('name' => 'doliprane', 'note' => 'mal de tete', 'is_active' => True));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testMedication6(){
        // pro create alergy other patient id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/medication/create/2', array('name' => 'doliprane', 'note' => 'mal de tete', 'is_active' => True));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 403);
    }

    // GET
    public function testMedication7(){
        // pro get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/medication/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","name": "doliprane","note": "mal de tete","is_active": "1"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","name": "doliprane","note": "mal de tete","is_active": "1"') !== false);
    
    }

    public function testMedication8(){
        // patient get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/medication/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","name": "doliprane","note": "mal de tete","is_active": "1"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","name": "doliprane","note": "mal de tete","is_active": "1"') !== false);
    }

	//PUT 
	public function testMedication9(){
        // pro update alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Put', '/pro/medication/update/1', array('name' => 'doliprane', 'note' => 'mal au ventre', 'is_active' => True));
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testMedication10(){
        // patient update alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Put', '/patient/medication/update/2', array('name' => 'doliprane', 'note' => 'mal au ventre', 'is_active' => True));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    // GET
    public function testMedication11(){
        // pro get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/medication/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","name": "doliprane","note": "mal au ventre","is_active": "1"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","name": "doliprane","note": "mal au ventre","is_active": "1"') !== false);
    }

    public function testMedication12(){
        // patient get alergy success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/medication/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","name": "doliprane","note": "mal au ventre","is_active": "1"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","name": "doliprane","note": "mal au ventre","is_active": "1"') !== false);
    }

	//DELETE 
	public function testMedication13(){
        // pro delete alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Delete', '/pro/medication/delete/1');
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testMedication14(){
        // patient delete alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Delete', '/patient/medication/delete/2');
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

	// GET
    public function testMedication15(){
        // pro get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/medication/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testMedication16(){
        // patient get alergy
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/medication/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

}