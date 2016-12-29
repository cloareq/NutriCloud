<?php

namespace NC\MessageBundle\Tests\Controller;

require  __DIR__ . '/../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    public function testMessage()
    {
        $this->loadFixtures(array("NC\\MessageBundle\\Tests\\LoadFixture"));
        // Pro get success
        $client = static::createClient();
     	$client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/message/get/1');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testMessage2()
    {
        // Patient get success
        $client = static::createClient();
	    $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
       	$client->request('Get', '/patient/message/get/1');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testMessage3()
    {
        // Pro send message success
		// http://beta.nutricloud.eu:3000
        $client = static::createClient();
    	$client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/message/new', array('patientId' => '1', 'text' => 'bonjour')); 
       	//echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 302);
    }

    public function testMessage4()
    {
        // Patient send message success
        $client = static::createClient();
	    $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/message/new', array('patientId' => '1', 'text' => 'bonjour')); 
        //echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 302);
    }

    public function testMessage5()
    {
        // Pro send message bad patientid
        $client = static::createClient();
    	$client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/message/new', array('patientId' => '10', 'text' => 'bonjour')); 
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testMessage6()
    {
        // Patient send message bad id
        $client = static::createClient();
	    $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/message/new', array('patientId' => '10', 'text' => 'bonjour')); 
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testMessage7()
    {
        // Pro send message bad patientid
        $client = static::createClient();
    	$client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/message/new', array('patientId' => '2', 'text' => 'bonjour')); 
		//echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 403);
    }

    public function testMessage8()
    {
        // Patient send message bad id
        $client = static::createClient();
	    $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/message/new', array('patientId' => '2', 'text' => 'bonjour')); 
		//echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 403);
    }

    public function testMessage9()
    {
        // Pro get success
        $client = static::createClient();
     	$client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/message/get/1');
        $this->assertTrue($client->getResponse()->getContent() != '[]');
    }

    public function testMessage10()
    {
        // Patient get success
        $client = static::createClient();
	    $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
       	$client->request('Get', '/patient/message/get/1');
        $this->assertTrue($client->getResponse()->getContent() != '[]');
    }
}
