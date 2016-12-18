<?php

namespace NC\ProBundle\Tests\Controller;

require  __DIR__ . '/../../../../vendor/autoload.php';
use Liip\FunctionalTestBundle\Test\WebTestCase;


class ProControllerTest extends WebTestCase
{
    //echo $client->getResponse()->getStatusCode() . $client->getResponse()->getContent() ;
   

    //// LOGIN
    public function testLogin()
    {
        $this->loadFixtures(array("NC\\ProBundle\\Tests\\LoadFixture"));
        // success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Vous \u00eates maintenant connect\u00e9 en tant que professionnel."}');
    }

    public function testLogin2()
    {
        // failure
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'failure', '_password' => 'failure'));
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Erreur lors dans la connexion, mauvais identifiant ou mot de passe."}');
    }

    //// GET INFORMATION
    public function testInfo()
    {

        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/info', array());
        $this->assertTrue($client->getResponse()->getContent() == '{"id": "1","firstName": "test_pro_1","lastName": "test_pro_1","userName": "test_pro_1","phone": "test_pro_1","city": "","postcode": "","address": "","mail": "test_pro_1"}');
             
    }

    //// UPDATE
    public function testUpdate1()
    {
        // username
        // Wrong username
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/update', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'test', 'password' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
    }

    public function testUpdate5()
    {
        // bad email address
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/update', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest', 'password' => 'testtest', 'mail' => 'testtest.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{"desc":"Adresse Email invalide."}');
    }

    public function testUpdate3()
    {
        // success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'test_pro_1', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/update', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest', 'password' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
        //$this->assertTrue($client->getResponse()->getContent() == '{}');
    }

    /* REGISTER*/
    
    public function testRegister()
    {
        // FAIL Post request
        $client = static::createClient();
        $client->request('Get', '/register');
        $this->assertTrue($client->getResponse()->getStatusCode() == 405);
        //$this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"La requ\u00eate doit \u00eatre de type POST."}');
    }
    public function testRegister2()
    {
        // email address
        $client = static::createClient();
        $client->request('Post', '/register', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest', 'password' => 'testtest', 'mail' => 'testtest.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Adresse Email invalide."}');
    }

    public function testRegister3()
    {
        // username
        $client = static::createClient();
        $client->request('Post', '/register', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'test', 'password' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le nom d\'utilisateur doit contenir au moins 6 caract\u00e8res."}');
    }

    public function testRegister4()
    {
        //password
        $client = static::createClient();
        $client->request('Post', '/register', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest', 'password' => 'tes', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Le mot de passe doit contenir au moins 6 caract\u00e8res."}');
    }

    public function testRegister5()
    {
        // FAIL username not available
        $client = static::createClient();
        $client->request('Post', '/register', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'testtest', 'password' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));   
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Compte cr\u00e9\u00e9 avec succ\u00e8s."}');
    }

    public function testRegister6()
    {
        // SUCCESS
        $client = static::createClient();
        $client->request('Post', '/register', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'patienttest', 'password' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 201);
    }

    public function testUpdate4()
    {
        // FAIL username not available
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'testtest', '_password' => 'test_pro_1'));
        $client->request('Post', '/pro/update', array('firstname' => 'test', 'lastname' => 'test', 'username' => 'patienttest', 'password' => 'testtest', 'mail' => 'test@test.com', 'phone' => '0102030405'));
        $this->assertTrue($client->getResponse()->getStatusCode() == 400);
        //$this->assertTrue($client->getResponse()->getContent() == '{}');
    }

    // Pro Delete
    public function testDelete()
    {
        // success
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'testtest', '_password' => 'test_pro_1'));
        $client->request('Get', '/pro/remove', array());
        $this->assertTrue($client->getResponse()->getStatusCode() == 200);
        //$this->assertTrue($client->getResponse()->getContent() == '{"state":"success","desc":"Le compte a \u00e9t\u00e9 supprim\u00e9 avec succ\u00e8s."}');
    }

    public function testDelete2()
    {
        // fail to relog
        $client = static::createClient();
        $client->request('Post', '/login_check', array('_username' => 'testtest', '_password' => 'testtest'));
        //echo "delete 2" + $client->getResponse()->getStatusCode();
        $this->assertTrue($client->getResponse()->getContent() == '{"state":"failure","desc":"Erreur lors dans la connexion, mauvais identifiant ou mot de passe."}');
    }
}
