<?php

namespace NC\ScheduleBundle\Tests;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NC\PlanBundle\Entity\PlanWeek;
use NC\ProBundle\Entity\Pro;
use NC\PatientBundle\Entity\Patient;

class LoadScheduleFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        echo 'Fixture_schedule ';
        $pro = new Pro();
        $pro->setUsername('pro_appointment');
        $pro->setFirstname('pro_appointment');
        $pro->setLastname('pro_appointment');
        $pro->setPassword('pro_appointment');
        $pro->setMail('pro_appointment@mail.com');
        $pro->setPhone('0102030455');
        $manager->persist($pro);
        $manager->flush();

        $patient = new Patient();
        $patient->setUsername('patient_appointment');
        $patient->setFirstname('patient_appointment');
        $patient->setLastname('patient_appointment');
        $patient->setPassword('patient_appointment');
        $patient->setMail('patient_appointment@mail.com');
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