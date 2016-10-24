<?php

namespace NC\PlanBundle\Tests;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NC\PlanBundle\Entity\PlanWeek;
use NC\ProBundle\Entity\Pro;
use NC\PatientBundle\Entity\Patient;

class LoadPlanFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        echo 'Fixture_plan';
        $pro = new Pro();
        $pro->setUsername('test_aliment');
        $pro->setFirstname('test_aliment');
        $pro->setLastname('test_aliment');
        $pro->setPassword('test_aliment');
        $pro->setMail('test_aliment@mail.com');
        $pro->setPhone('0299436958');
        $manager->persist($pro);
        $manager->flush();


        $patient = new Patient();
        $patient->setUsername('test_aliment2');
        $patient->setFirstname('test_aliment2');
        $patient->setLastname('test_aliment2');
        $patient->setPassword('test_alimen2t');
        $patient->setMail('test_aliment2@mail.com');
        $patient->setPhone('02994369582');


        $plan_week = new PlanWeek();
        #$plan_week->setPatient($patient);
        $manager->persist($plan_week);
        $manager->flush();

        $patient->setPlanModel($plan_week);
        $patient->setPro($pro);
        $manager->persist($patient);
        $manager->flush();
    }
}