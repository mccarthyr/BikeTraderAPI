<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/20/15
 * Time: 9:26 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Model;

/**
 * Class Bicycle
 * Storage agnostic Bicycle object
 *
 * Extend this and provide your own metadata mappings
 *
 * @package SoftwareDesk\BikeTraderAPIBundle\Model
 *
 */
abstract class Bicycle implements BicycleInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $type;

}

