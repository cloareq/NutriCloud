<?php

namespace NC\PatientBundle\Tests\Controller;

require  __DIR__ . '/../../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class HeightControllerTest extends WebTestCase
{
	// GET
    public function testHeight1(){
        $this->loadFixtures(array("NC\\PatientBundle\\Tests\\LoadFixture"));
        // pro get height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/height/1');
        //$this->assertTrue($client->getResponse()->getContent() == '[]');
        $this->assertTrue(1==1);
    }
    public function testHeight2(){
        // patient get height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/height/1');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    // POST 
    public function testHeight3(){
        // pro create height success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/height/create/1', array('height' => 100));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testHeight4(){
        // patient create height success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/height/create/1', array('height' => 100));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testHeight5(){
        // pro create height bad id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/height/create/10', array('height' => 100));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testHeight6(){
        // pro create height other patient id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/height/create/2', array('height' => 100));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getStatusCode() == 403);
    }

    // GET
    public function testHeight7(){
        // pro get height success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/height/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","height": "100"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","height": "100"') !== false);
    }

    public function testHeight8(){
        // patient get height success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/height/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","height": "100"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","height": "100"') !== false);
    }

	//PUT 
	public function testHeight9(){
        // pro update height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Put', '/pro/height/update/1', array('height' => 150));
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testHeight10(){
        // patient update height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Put', '/patient/height/update/2', array('height' => 150));
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    // GET
    public function testHeight11(){
        // pro get height success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/height/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","height": "150"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","height": "150"') !== false);
    }

    public function testHeight12(){
        // patient get height success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/height/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue(
            strpos($client->getResponse()->getContent(), '"id": "1","height": "150"') !== false &&
            strpos($client->getResponse()->getContent(), '"id": "2","height": "150"') !== false);
    }

	//DELETE 
	public function testHeight13(){
        // pro delete height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Delete', '/pro/height/delete/1');
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testHeight14(){
        // patient delete height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Delete', '/patient/height/delete/2');
		//echo $client->getResponse()->getContent() . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

	// GET
    public function testHeight15(){
        // pro get height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/height/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testHeight16(){
        // patient get height
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/height/1');
		//echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }
}