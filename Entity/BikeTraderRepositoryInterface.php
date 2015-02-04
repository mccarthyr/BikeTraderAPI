<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/22/15
 * Time: 7:38 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Entity;

use SoftwareDesk\BikeTraderAPIBundle\Model\BicycleInterface;

interface BikeTraderRepositoryInterface
{
    /**
     * @param $type
     * @return Bicycle[]
     */
    public function findBikeByType($type);

    public function findBikeById($id);

    public function getPrimaryKeyForEntity($entity);

    public function save(BicycleInterface $bicycle);

    public function update();

    public function delete(BicycleInterface $bicycle);

}

