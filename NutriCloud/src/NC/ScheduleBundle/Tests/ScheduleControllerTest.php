<?php

namespace NC\ScheduleBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ScheduleControllerTest extends WebTestCase
{
    public function testGetSchedule()
    {
        echo 'Schedule';
        $this->loadFixtures(array("NC\\ScheduleBundle\\Tests\\LoadScheduleFixture"));
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/all', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testGetPatientSchedule()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/patient/1', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testGetScheduleBetweenTwoDates()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/2015-12-09T10:29:00.00Z/2015-12-09T10:45:00.01Z', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testPatientGetSchedule()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'patient_appointment', '_password' => 'patient_appointment'));
        $client->request('Get', '/patient/appointment/get', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    /**//* CREATE *//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    public function testCreateAppointment()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Post', '/pro/appointment/create/1', array("start" => "2015-12-09T10:30:00.00Z",
            "end" => "2015-12-09T10:45:00.00Z",
            "comment" => "checkup mensuel"));
        #echo $client->getResponse()->getContent();
        #$this->assertTrue( == '{"state":"success","desc":"Vous \u00eates maintenant connect\u00e9 en tant que professionnel."}9');
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testGetSchedule2()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/all', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "checkup mensuel","start": "2015-12-09T10:30:00.00Z","end": "2015-12-09T10:45:00.00Z"}]');
    }

    public function testGetPatientSchedule2()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/patient/1', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "checkup mensuel","start": "2015-12-09T10:30:00.00Z","end": "2015-12-09T10:45:00.00Z"}]');
    }

    public function testGetScheduleBetweenTwoDates2()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/2015-12-09T10:29:00.00Z/2015-12-09T10:45:00.01Z', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "checkup mensuel","start": "2015-12-09T10:30:00.00Z","end": "2015-12-09T10:45:00.00Z"}]');
    }

    public function testGetScheduleBetweenTwoDates3()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/2014-12-09T10:29:00.00Z/2014-12-09T10:45:00.01Z', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }


    public function testPatientGetSchedule2()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'patient_appointment', '_password' => 'patient_appointment'));
        $client->request('Get', '/patient/appointment/get', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "checkup mensuel","start": "2015-12-09T10:30:00.00Z","end": "2015-12-09T10:45:00.00Z"}]');
    }

    /**//* UPDATE *//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    public function testUpdateAppointment()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Post', '/pro/appointment/update/1', array("start" => "2015-12-09T20:30:00.00Z",
            "end" => "2015-12-09T20:45:00.00Z",
            "comment" => "updated checkup mensuel"));
        #echo $client->getResponse()->getContent();
        #$this->assertTrue( == '{"state":"success","desc":"Vous \u00eates maintenant connect\u00e9 en tant que professionnel."}9');
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }

    public function testGetSchedule3()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/all', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "updated checkup mensuel","start": "2015-12-09T20:30:00.00Z","end": "2015-12-09T20:45:00.00Z"}]');
    }

    public function testGetPatientSchedule3()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/patient/1', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "updated checkup mensuel","start": "2015-12-09T20:30:00.00Z","end": "2015-12-09T20:45:00.00Z"}]');
    }

    public function testGetScheduleBetweenTwoDates4()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/2015-12-07T00:00:00.00Z/2015-12-10T00:00:00.00Z', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "updated checkup mensuel","start": "2015-12-09T20:30:00.00Z","end": "2015-12-09T20:45:00.00Z"}]');
    }

    public function testPatientGetSchedule3()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'patient_appointment', '_password' => 'patient_appointment'));
        $client->request('Get', '/patient/appointment/get', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","title": "updated checkup mensuel","start": "2015-12-09T20:30:00.00Z","end": "2015-12-09T20:45:00.00Z"}]');
    }

    /**//* DELETE *//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**//**/
    public function testDeleteAppointment()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Delete', '/pro/appointment/remove/1', array());
        #echo $client->getResponse()->getContent();
        #$this->assertTrue( == '{"state":"success","desc":"Vous \u00eates maintenant connect\u00e9 en tant que professionnel."}9');
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
    }


    public function testGetPatientSchedule4()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/patient/1', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testGetScheduleBetweenTwoDates5()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'pro_appointment', '_password' => 'pro_appointment'));
        $client->request('Get', '/pro/appointment/get/2015-12-09T10:29:00.00Z/2015-12-09T10:45:00.01Z', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

    public function testPatientGetSchedule4()
    {
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'patient_appointment', '_password' => 'patient_appointment'));
        $client->request('Get', '/patient/appointment/get', array());
        #$client->request('Get', '/pro/patient/list', array());
        #echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[]');
    }

}