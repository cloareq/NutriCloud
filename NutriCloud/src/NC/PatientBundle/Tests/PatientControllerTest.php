<?php

namespace NC\PatientBundle\Tests\Controller;

require  __DIR__ . '/../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase
{
    //// LOGIN
    public function testLogin(){
        $this->loadFixtures(array("NC\\PatientBundle\\Tests\\LoadFixture"));
        // success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Vous \u00eates maintenant connect\u00e9 en tant que patient."}');
    }

    public function testLogin2(){
        // failure
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'failure', '_password' => 'failure'));
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Erreur lors dans la connexion, mauvais identifiant ou mot de passe."}');
    }

    //// GET INFORMATION
    public function testInfo(){
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/info', array());
        $this->assertTrue($client->getResponse()->getContent() == '{"id": "1", "firstName": "test_patient_1", "lastName": "test_patient_1", "userName": "test_patient_1", "phone": "test_patient_1", "mail": "test_patient_1", "city": "", "postcode": "", "address": "", "mail": "test_patient_1"}');
    }

    //// UPDATE
    public function testUpdate1(){
        // Username
        // Wrong username
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/update/1', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'test', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
    }
    
    public function testUpdate5(){
        // bad email address
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/update/1', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest', 'mail' => 'testtest.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{"desc":"Adresse Email invalide."}');
    }

    public function testUpdate3(){
        // success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_patient_1', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/update/1', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
        //$this->assertTrue($client->getResponse()->getContent() == '{}');
    }
    /*
    public function testUpdate4()
    {
        // FAIL username not available
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'testtest', '_password' => 'test_patient_1'));
        $client->request('Post', '/patient/update/1', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'test_pro_1', 'password' => 'test_patient_1', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        echo $client->getResponse()->getStatusCode() . $client->getResponse()->getContent() ;
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{}');
    }
    */

    // Patient Delete
    public function testDelete(){
        // success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'testtest', '_password' => 'test_patient_1'));
        $client->request('Get', '/patient/remove', array());
        //$this->assertTrue($client->getResponse()->getStatusCode() == 200);
        $this->assertTrue($client->getResponse()->getContent() == '{"desc":"Compte supprim\u00e9"}');
    }

    public function testDelete2(){
        // fail to relog
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'testtest', '_password' => 'test_patient_1'));
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Erreur lors dans la connexion, mauvais identifiant ou mot de passe."}');
    }

    //// NEW PATIENT
    public function testNewPatient(){
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/patient/new', array('username' => 'test_patient_2',
            'firstname' => 'test_patient_2',
            'lastname' => 'test_patient_2',
            'password' => 'test_patient_2',
            'mail' => 'patient2@patient.com',
            'phone' => '0102030178'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testNewPatient4(){
        // new patient failed bad mail
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/patient/new', array('username' => 'patient23',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient2',
            'mail' => 'patient23patient.com',
            'phone' => '0102030178'));
        $this->assertTrue($client->getResponse()->getContent() == '{"desc":"Adresse Email invalide."}');
    }

    public function testNewPatient5(){
        // new patient too short password
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/patient/new', array('username' => 'patient234',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'pat',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
        $this->assertTrue($client->getResponse()->getContent() == '{"desc":"Le mot de passe doit contenir au moins 6 caract\u00e8res."}');
    }

    public function testNewPatient6(){
        // new patient too short login
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/patient/new', array('username' => 'pat',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
        $this->assertTrue($client->getResponse()->getContent() == '{"desc":"Le nom d\'utilisateur doit contenir au moins 6 caract\u00e8res."}');
    }

   public function testNewPatient2(){
        // new patient failed same login
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/patient/new', array('username' => 'test_pro_1',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient2',
            'mail' => 'patient23@patient.com',
            'phone' => '0102030178'));
        $this->assertTrue($client->getResponse()->getContent() == '{"desc":"Username d\u00e9j\u00e0 utilis\u00e9."}');
    }

    public function testUpdatePatient2(){
        // update patient which does not exist
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/update/200', array('username' => 'patientt',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
        $this->assertTrue($client->getResponse()->getContent() == '{"desc":"Le patient recherch\u00e9 n\'existe pas."}');
    }

    public function testGetInfosPatient(){
        // pro get patient info
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/patient/1', array());
        $this->assertTrue($client->getResponse()->getContent() == '{"id": "1", "firstName": "test_patient_2", "lastName": "test_patient_2", "userName": "test_patient_2", "phone": "0102030178", "mail": "patient2@patient.com", "city": "", "postcode": "", "address": "", "mail": "patient2@patient.com"}');
    }

    public function testListPatients(){
        // list patient success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/patient/list', array());
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","firstName": "test_patient_2", "lastName": "test_patient_2", "phone": "0102030178", "mail": "patient2@patient.com", "city": "", "postcode": "", "address": "", "mail": "patient2@patient.com"}]');
    }
}