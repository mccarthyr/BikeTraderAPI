<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 2/3/15
 * Time: 12:27 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Utils;

use JMS\Serializer\Serializer;

class ConvertingEntityToObject
{

    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this -> serializer = $serializer;
    }


    public function convertEntityToObject($entityToConvert)
    {
        $jsonContent = $this -> serializer -> serialize($entityToConvert, 'json');
        $entityConvertedToObject = json_decode($jsonContent);
        return $entityConvertedToObject;
    }




} 