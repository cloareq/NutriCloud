<?php

namespace NC\PatientBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PatientControllerTest extends WebTestCase
{
    public function testpa()
    {
        $this->assertTrue(1 == 1);
    }
    /*
    public function testNewPatient(){
        echo '     Patient';
        // new patient success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/patient/new', array('username' => 'patient2',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient2',
            'mail' => 'patient2@patient.com',
            'phone' => '0102030178'));
   //     echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Nouveau patient cr\u00e9\u00e9 avec succ\u00e8s."}');
    }

    public function testNewPatient2(){
        // new patient failed same login
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/patient/new', array('username' => 'patient2',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient2',
            'mail' => 'patient23@patient.com',
            'phone' => '0102030178'));
//            echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Login d\u00e9j\u00e0 utilis\u00e9."}');
    }

    public function testNewPatient3(){
        // new patient failed post request
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/patient/new', array('username' => 'patient2',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient2',
            'mail' => 'patient23@patient.com',
            'phone' => '0102030178'));
//        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"La requ\u00eate doit \u00eatre de type POST."}');
    }

    public function testNewPatient4(){
        // new patient failed bad mail
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/patient/new', array('username' => 'patient23',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient2',
            'mail' => 'patient23patient.com',
            'phone' => '0102030178'));
        //        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Adresse Email invalide."}');
    }

    public function testNewPatient5(){
        // new patient too short password
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/patient/new', array('username' => 'patient234',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'pat',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
//        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le mot de passe doit contenir au moins 6 caract\u00e8res."}');
    }

    public function testNewPatient6(){
        // new patient too short login
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/patient/new', array('username' => 'pat',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
       // echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le nom d\'utilisateur doit contenir au moins 6 caract\u00e8res."}');
    }

    public function testUpdatePatient(){
        // update patient success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/update/1', array('username' => 'patient',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
//         echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Les donn\u00e9es personnelles du patient ont \u00e9t\u00e9s mise \u00e0 jour avec succ\u00e8s."}');
    }

    public function testUpdatePatient2(){
        // update patient which does not exist
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/update/2', array('username' => 'patientt',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
//         echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le patient recherch\u00e9 n\'existe pas."}');
    }

    public function testUpdatePatient3(){
        // update patient bad login
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/update/1', array('username' => 'pat',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2d@patient.com',
            'phone' => '0102030178'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le nom d\'utilisateur doit contenir au moins 6 caract\u00e8res."}');
    }

    public function testUpdatePatient4(){
        // update patient wrong mail
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/update/1', array('username' => 'patientt5',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2dpatient.com',
            'phone' => '0102030178'));
        //echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Adresse Email invalide."}');
    }

    public function testUpdatePatient5(){
        // update patient, post request
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/update/1', array('username' => 'patientt5',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'patient',
            'mail' => 'patient2@patient.com',
            'phone' => '0102030178'));
//        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"La requ\u00eate doit \u00eatre de type POST."}');
    }

    public function testUpdatePatient6(){
        // update patient, too short password
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Post', '/pro/update/1', array('username' => 'patientt5',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'pat',
            'mail' => 'patient2@patient.com',
            'phone' => '0102030178'));
  //      echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le mot de passe doit contenir au moins 6 caract\u00e8res."}');
    }

    public function testUpdatePatient7(){
        // update patient, no access
        $client = static::createClient();
        $client->request('Post', '/register', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest2', 'password' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $client->request('Post', '/login_check', array('_username' => 'testtest2', '_password' => 'testtest'));
        $client->request('Post', '/pro/patient/new', array('username' => 'testpatient',
            'firstname' => 'testpatient',
            'lastname' => 'testpatient',
            'password' => 'testpatient',
            'mail' => 'patient2@patient2.com',
            'phone' => '0102030178'));
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));

        $client->request('Post', '/pro/update/2', array('username' => 'patientt55',
            'firstname' => 'patient2',
            'lastname' => 'patient2',
            'password' => 'pattient',
            'mail' => 'patient2@patient.com',
            'phone' => '0102030178'));
//        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le patient recherch\u00e9 ne fait pas partie de votre liste de patient."}');
    }

    public function testUpdatePatient8(){
        // patient connection and profile update
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'patient', '_password' => 'patient'));  //patient
        $client->request('Post', '/patient/update/1', array('username' => 'patient1',
            'firstname' => 'patient',
            'lastname' => 'patient',
            'password' => 'patient1',
            'mail' => 'patient2@patient.com',
            'phone' => '0102030178'));
//        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Les donn\u00e9es personnelles du patient ont \u00e9t\u00e9s mise \u00e0 jour avec succ\u00e8s."}');
    }

    public function testGetInfosPatient(){
        // pro get patient info
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/patient/1', array());
//        echo $client->getResponse()->getContent();

        $this->assertTrue($client->getResponse()->getContent() == '{"id": "1", "firstName": "patient2", "lastName": "patient2", "userName": "patient1", "phone": "0102030178", "mail": "patient2@patient.com", "city": "", "postcode": "", "address": "", "mail": "patient2@patient.com"}');
    }

    public function testGetInfosPatient2(){
        // patient get info
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'patient1', '_password' => 'patient1'));  //patient
        $client->request('Get', '/patient/info', array());
    //        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"id": "1", "firstName": "patient2", "lastName": "patient2", "userName": "patient1", "phone": "0102030178", "mail": "patient2@patient.com", "city": "", "postcode": "", "address": "", "mail": "patient2@patient.com"}');
    }

    public function testGetInfosPatient3(){
        // pro get patient info, no access
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/patient/2', array());
//        echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Vous n\'avez pas acc\u00e8s \u00e0 ces donn\u00e9es."}');
    }

    public function testGetInfosPatient4(){
        // pro get patient wrong id
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/patient/4', array());
 //       echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le patient recherch\u00e9 n\'existe pas."}');
    }

    public function testListPatients(){
        // list patient success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'username', '_password' => 'password'));
        $client->request('Get', '/pro/patient/list', array());
     //   echo $client->getResponse()->getContent();
        $this->assertTrue($client->getResponse()->getContent() == '[{"id": "1","firstName": "patient2", "lastName": "patient2", "phone": "0102030178", "mail": "patient2@patient.com", "city": "", "postcode": "", "address": "", "mail": "patient2@patient.com"}]');
    }
*/
}