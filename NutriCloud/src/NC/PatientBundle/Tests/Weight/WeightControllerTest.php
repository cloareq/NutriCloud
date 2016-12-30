<?php

namespace NC\PatientBundle\Tests\Controller;

require  __DIR__ . '/../../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class WeightControllerTest extends WebTestCase
{
	// GET
    public function testWeight1(){
        $this->loadFixtures(array("NC\\PatientBundle\\Tests\\LoadFixture"));
        // pro get weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/weight/1');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }
    public function testWeight2(){
        // patient get weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/weight/1');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    // POST 
    public function testWeight3(){
        // pro create weight success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/weight/create/1', array('weight' => 100));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testWeight4(){
        // patient create weight success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/weight/create/1', array('weight' => 100));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testWeight5(){
        // pro create weight bad id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/weight/create/10', array('weight' => 100));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testWeight6(){
        // pro create weight other patient id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/weight/create/2', array('weight' => 100));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 403);
    }

    // GET
    public function testWeight7(){
        // pro get weight success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/weight/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","weight": "100","date"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","weight": "100","date"') !== false);
    }

    public function testWeight8(){
        // patient get weight success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/weight/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","weight": "100","date"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","weight": "100","date"') !== false);
    }

	//PUT 
	public function testWeight9(){
        // pro update weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Put', '/pro/weight/update/1', array('weight' => 120));
           $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testWeight10(){
        // patient update weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Put', '/patient/weight/update/2', array('weight' => 120));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    // GET
    public function testWeight11(){
        // pro get weight success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/weight/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","weight": "120","date"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","weight": "120","date"') !== false);
    }

    public function testWeight12(){
        // patient get weight success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/weight/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","weight": "120","date"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","weight": "120","date"') !== false);
    }

	//DELETE 
	public function testWeight13(){
        // pro delete weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Delete', '/pro/weight/delete/1');
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testWeight14(){
        // patient delete weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Delete', '/patient/weight/delete/2');
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

	// GET
    public function testWeight15(){
        // pro get weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/weight/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testWeight16(){
        // patient get weight
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/weight/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }
}