<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/20/15
 * Time: 2:43 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Model;

/**
 * Interface BikeTraderManagerInterface
 * @package SoftwareDesk\BikeTraderAPIBundle\Model
 *
 * Interface to be implemented by Bike Trader Managers.
 * This is an additional level of abstraction between your
 * application and the actual repository.
 */

interface BikeTraderManagerInterface
{
    public function createBike($bike);

    public function retrieveBike(array $retrievalOptions);

    public function updateBike();

    public function deleteBike($id);
}


