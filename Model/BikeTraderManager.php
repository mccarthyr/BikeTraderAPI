<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/20/15
 * Time: 2:51 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Model;

/**
 * Class BikeTraderManager
 * @package SoftwareDesk\BikeTraderAPIBundle\Model
 *
 * Abstract Bike Trader Manager implementation which can be
 * used as a base class for your concrete bike trader manager.
 */
abstract class BikeTraderManager implements BikeTraderManagerInterface
{

    abstract public function createBike($bike);

    abstract public function retrieveBike(array $retrievalOptions);

    abstract public function updateBike();

    abstract public function deleteBike($id);


}

