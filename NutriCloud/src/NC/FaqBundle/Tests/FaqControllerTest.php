<?php

namespace NC\FaqBundle\Tests\Controller;

require  __DIR__ . '/../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class FaqControllerTest extends WebTestCase{

    public function testFaq()
    {
        $this->loadFixtures(array("NC\\FaqBundle\\Tests\\LoadFixture"));
        // pro get FAQ
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/faq/');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testFaq2()
    {
        // patient get FAQ
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/faq/');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testFaq3()
    {
        // new faq elem success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/faq/new', array('question' => 'question ?', 'response' => 'reponse !', 'category' => 'category'));
        //echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testFaq4()
    {
        // update info success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/faq/update/1', array('question' => 'question updated', 'response' => 'reponse updated', 'category' => 'category'));
        //echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testFaq5()
    {
        // update info  fail
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/faq/update/100', array('question' => 'question updated', 'response' => 'reponse updated', 'category' => 'category'));
        //echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getContent() == '{"desc":"L\'\u00e9l\u00e9ment que vous cherchez \u00e0 modifier n\'existe pas."}');
    }

    public function testFaq6()
    {
        // pro get FAQ
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/faq/');
        //echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1", "category": "category", "question": "question updated","response": "reponse updated"}]');
    }

    public function testFaq7()
    {
        // patient get FAQ
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/faq/');
        //echo $client->getResponse()->getContent()  . $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1", "category": "category", "question": "question updated","response": "reponse updated"}]');
    }

    public function testFaq8()
    {
        // delete faq success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/faq/remove/1');
        //echo $client->getResponse()->getContent() . 
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testFaq9()
    {
        // delete faq success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/faq/remove/10');
        //echo $client->getResponse()->getContent() . 
        $this->assertTrue($client->getResponse()->getStatusCode() == 404);
    }

    public function testFaq10()
    {
        // pro get FAQ
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/faq/');
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }
}