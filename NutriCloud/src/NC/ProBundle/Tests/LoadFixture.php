<?php

namespace NC\ProBundle\Tests;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NC\ProBundle\Entity\Pro;

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
        $manager->persist($pro);
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