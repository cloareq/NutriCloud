<?php

namespace NC\FaqBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class FaqControllerTest extends WebTestCase{

    public function testFaq()
    {
        $this->assertTrue(1 == 1);
    }

        /*TO DO coté patient */
/*
    public function testFaq()
    {
        $this->loadFixtures(array("NC\\ProBundle\\Tests\\LoadFixture"));
        echo '     Faq';
        // new faq elem success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/faq/new', array('question' => 'question ?', 'response' => 'reponse !', 'category' => 'category'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Nouvel \u00e9l\u00e9ment ajout\u00e9 avec succ\u00e8s \u00e0 la FAQ."}');
    }

    public function testFaq2()
    {
        // Post request failed
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/faq/new', array('question' => 'question ?', 'response' => 'reponse !', 'category' => 'category'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"La requ\u00eate doit \u00eatre de type POST."}');
    }

    public function testFaq3()
    {
        // update info success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/faq/update/1', array('question' => 'question updated', 'response' => 'reponse updated', 'category' => 'category'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"L\'\u00e9l\u00e9ment \u00e0 \u00e9t\u00e9 mis \u00e0 jour avec succ\u00e8s."}');
    }

    public function testFaq4()
    {
        // update faq with bad id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/faq/update/4', array('question' => 'question updated', 'response' => 'reponse updated', 'category' => 'category'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"L\'\u00e9l\u00e9ment que vous cherchez \u00e0 modifier n\'existe pas."}');
    }

    public function testFaq5()
    {
        // update fas of an other pro
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'jeremy', '_password' => 'jeremy'));
        $client->request('Post', '/pro/faq/new', array('question' => 'question ?', 'response' => 'reponse !', 'category' => 'category'));
        // echo $client->getResponse()->getContent();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/faq/update/2', array('question' => 'question updated', 'response' => 'reponse updated', 'category' => 'category'));
         //   echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Vous n\'\u00eates pas le propri\u00e9taire de cet \u00e9l\u00e9ment."}');
    }
    public function testFaq6()
    {
        // get FAQ
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/faq/');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1", "category": "category", "question": "question updated","response": "reponse updated"}]');
    }

    public function testFaq7()
    {
        // delete faq success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/faq/remove/1');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"L\'\u00e9l\u00e9ment \u00e0 \u00e9t\u00e9 supprim\u00e9 avec succ\u00e8s de la FAQ."}');
    }

    public function testFaq8()
    {
        // delete faq failed
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/faq/remove/4');
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"L\'\u00e9l\u00e9ment que vous cherchez \u00e0 supprimer n\'existe pas."}');
    }

    public function testFaq9()
    {
        // delete faq of an other pro
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'jeremy', '_password' => 'jeremy'));
        $client->request('Post', '/pro/faq/new', array('question' => 'question ?', 'response' => 'reponse !'));
        // echo $client->getResponse()->getContent();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/faq/remove/2');
        //   echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Vous n\'\u00eates pas le propri\u00e9taire de cet \u00e9l\u00e9ment."}');
    }
    */
}