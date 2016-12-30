<?php

namespace NC\MessageBundle\Tests;

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


        $pro2 = new Pro();
        $pro2->setUsername('test_pro_2');
        $pro2->setFirstname('test_pro_1');
        $pro2->setLastname('test_pro_1');
        $pro2->setPassword('test_pro_1');
        $pro2->setMail('test_pro_1');
        $pro2->setPhone('test_pro_1');
        $pro2->setPasswordRecoveryHash("");
        $manager->persist($pro2);
        $manager->flush();

        $patient2 = new Patient();
        $patient2->setUsername('test_patient_2');
        $patient2->setFirstname('test_patient_1');
        $patient2->setLastname('test_patient_1');
        $patient2->setPassword('test_patient_1');
        $patient2->setMail('test_patient_1');
        $patient2->setPhone('test_patient_1');
        $patient2->setPro($pro2);
        $pro2->addPatient($patient2);
        $patient2->setPasswordRecoveryHash("");
        $manager->persist($patient2);
        $manager->flush();

    }
}