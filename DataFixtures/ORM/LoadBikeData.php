<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/26/15
 * Time: 10:11 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SoftwareDesk\BikeTraderAPIBundle\Entity\Bicycle;

class LoadBikeData implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $bike = new Bicycle();
        $bike -> setName('Prestigio Fixture');
        $bike -> setDescription('Fixture: Test data description');
        $bike -> setType('road');

        $manager -> persist($bike);
        $manager -> flush();
    }

} 