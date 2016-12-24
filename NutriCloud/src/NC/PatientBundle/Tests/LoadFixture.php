<?php

namespace NC\PatientBundle\Tests;

require  __DIR__ . '/../../../../vendor/autoload.php';
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NC\ProBundle\Entity\Pro;
use NC\PatientBundle\Entity\Patient;

class LoadFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $pro = new Pro();
        $pro->setUsername('test_pro_1');
        $pro->setFirstname('test_pro_1');
        $pro->setLastname('test_pro_1');
        $pro->setPassword('test_pro_1');
        $pro->setMail('test_pro_1');
        $pro->setPhone('test_pro_1');
        $pro->setPasswordRecoveryHash("");
        $manager->persist($pro);
        $manager->flush();

        $patient = new Patient();
        $patient->setUsername('test_patient_1');
        $patient->setFirstname('test_patient_1');
        $patient->setLastname('test_patient_1');
        $patient->setPassword('test_patient_1');
        $patient->setMail('test_patient_1');
        $patient->setPhone('test_patient_1');
        $patient->setPro($pro);
        $pro->addPatient($patient);
        $patient->setPasswordRecoveryHash("");
        $manager->persist($patient);
        $manager->flush();

    /*
        $pro = new Pro();
        $pro->setUsername('jeremy');
        $pro->setFirstname('jeremy');
        $pro->setLastname('jeremy');
        $pro->setPassword('jeremy');
        $pro->setMail('jeremy@mail.com');
        $pro->setPhone('0102030405');
        $manager->persist($pro);
        echo ".";

      $pro = new Pro();
        $pro->setUsername('test_aliment');
        $pro->setFirstname('test_aliment');
        $pro->setLastname('test_aliment');
        $pro->setPassword('test_aliment');
        $pro->setMail('test_aliment@mail.com');
        $pro->setPhone('0299436958');
        $manager->persist($pro);
        $manager->flush();
    */
    }
}