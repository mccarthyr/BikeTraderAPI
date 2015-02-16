<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/26/15
 * Time: 10:11 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SoftwareDesk\BikeTraderAPIBundle\Document\Bicycle;

class LoadBikeData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $bike = new Bicycle();
        $bike -> setName('Prestigio MongoDB Fixture');
        $bike -> setDescription('MongoDB Fixture: Test data description');
        $bike -> setType('road');

        $dateTime = new \DateTime();
        $bike -> setCreatedAt($dateTime -> getTimestamp());

        $manager -> persist($bike);
        $manager -> flush();
    }

}