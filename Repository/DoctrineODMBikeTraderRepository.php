<?php

namespace SoftwareDesk\BikeTraderAPIBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use SoftwareDesk\BikeTraderAPIBundle\Entity\BikeTraderRepositoryInterface;
use SoftwareDesk\BikeTraderAPIBundle\Model\BicycleInterface;

//use SoftwareDesk\BikeTraderAPIBundle\Document\Bicycle;

/**
 * DoctrineODMBikeTraderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * This class is specifically coupled with the Doctrine ORM.
 * It implements the persistence storage agnostic interface of
 * BikeTraderRepositoryInterface which is injected into the
 * BikeTraderManager.
 */
class DoctrineODMBikeTraderRepository extends DocumentRepository implements BikeTraderRepositoryInterface
{


    public function findBikeByType($type)
    {
        $bike = $this -> getDocumentManager() -> getRepository('SoftwareDeskBikeTraderAPIBundle:Bicycle')
                ->findBy(array('type' => $type));
        return $bike;
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
        $em = $this -> getDocumentManager();
        $em -> persist($bicycle);
        $em -> flush();
    }

    public function findBikeById($id)
    {
        $bike = $this -> getDocumentManager()->getRepository('SoftwareDeskBikeTraderAPIBundle:Bicycle')
            ->find($id);
        return $bike;
    }


    public function getPrimaryKeyForEntity($entity)
    {
        $em = $this -> getDocumentManager();
        $meta = $em -> getClassMetadata(get_class($entity));
        $identifierArray = $identifier = $meta->getIdentifier();

        // The first element in the array (and the only one) is the primary key.
        $primaryKey = $identifierArray[0];
        return $primaryKey;
    }



    // ** SEE IF THIS IS THE BEST LOCATION FOR THIS ???
    // WAIT UNTIL THE MAIN CODE IS REDESIGNED FIRST BEFORE LOOKING AT THIS LOCATION...
    public function update()
    {
        $em = $this -> getDocumentManager();
        $em -> flush();
    }

    public function delete(BicycleInterface $bicycle)
    {
        $em = $this -> getDocumentManager();
        $em -> remove($bicycle);
        $em -> flush();
    }



}
