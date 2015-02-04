<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/22/15
 * Time: 7:46 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Entity;

use Doctrine\ORM\EntityRepository;
use SoftwareDesk\BikeTraderAPIBundle\Entity\BikeTraderRepositoryInterface;
//use SoftwareDesk\BikeTraderAPIBundle\Entity\Bicycle;

use SoftwareDesk\BikeTraderAPIBundle\Model\BicycleInterface;

/**
 * Class DoctrineORMBikeTraderRepository
 * @package SoftwareDesk\BikeTraderAPIBundle\Entity
 *
 * This class is specifically coupled with the Doctrine ORM.
 * It implements the persistence storage agnostic interface of
 * BikeTraderRepositoryInterface which is injected into the
 * BikeTraderManager.
 */
class DoctrineORMBikeTraderRepository extends EntityRepository implements BikeTraderRepositoryInterface
{

    public function findBikeByType($type)
    {

        $bike = $this -> getEntityManager() -> getRepository('SoftwareDeskBikeTraderAPIBundle:Bicycle')
            -> findBy(array('type' => $type));
        return $bike;
    }


    public function findBikeById($id)
    {
        $bike = $this -> getEntityManager()->getRepository('SoftwareDeskBikeTraderAPIBundle:Bicycle')
            ->find($id);
        return $bike;
    }


    public function getPrimaryKeyForEntity($entity)
    {
        $em = $this -> getEntityManager();
        $meta = $em -> getClassMetadata(get_class($entity));
        $primaryKey = $identifier = $meta->getSingleIdentifierFieldName();
        // THis only works when an entity has a single primary key (not for composites)
        // For composites: $meta->getIdentifierFieldNames()
        return $primaryKey;
    }




    /**
     * @param Bicycle $bicycle
     *
     * Put the save method here so do not require an entity manager or
     * the more general Manager Registry injected into the Bike Trader Manager
     * class which keep it less coupled.
     */
    public function save(BicycleInterface $bicycle)
    {
        $em = $this -> getEntityManager();
        $em -> persist($bicycle);
        $em -> flush();
    }



    // ** SEE IF THIS IS THE BEST LOCATION FOR THIS ???
    // WAIT UNTIL THE MAIN CODE IS REDESIGNED FIRST BEFORE LOOKING AT THIS LOCATION...
    public function update()
    {
        $em = $this -> getEntityManager();
        $em -> flush();
    }

    public function delete(BicycleInterface $bicycle)
    {
        $em = $this -> getEntityManager();
        $em -> remove($bicycle);
        $em -> flush();
    }

}


