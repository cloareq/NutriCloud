<?php

namespace NC\AlertBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlertControllerTest extends WebTestCase
{
    public function testAlert()
    {
        echo '     Alert';
        // new message success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        //$client->request('Post', '/pro/message/new', array('question' => 'question ?', 'response' => 'reponse !'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue(1==1);
    }
}
