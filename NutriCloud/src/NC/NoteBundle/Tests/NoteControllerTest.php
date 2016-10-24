<?php

namespace NC\NoteBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NoteControllerTest extends WebTestCase
{
    public function testNote()
    {
        //        $this->loadFixtures(array("NC\\ProBundle\\Tests\\Controller\\LoadFixture"));
        echo '     Note';
        // new message success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
//        $client->request('Post', '/pro/message/new', array('question' => 'question ?', 'response' => 'reponse !'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue(1==1);
    }}
