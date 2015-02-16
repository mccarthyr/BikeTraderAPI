<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/20/15
 * Time: 10:12 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Entity;

use SoftwareDesk\BikeTraderAPIBundle\Model\BikeTraderManager as BaseBikeTraderManager;
use SoftwareDesk\BikeTraderAPIBundle\Entity\Bicycle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;


use Symfony\Component\DependencyInjection\ContainerInterface;

//use Doctrine\ORM\EntityRepository;
//use Doctrine\ORM\EntityManager;
//use SoftwareDesk\BikeTraderAPIBundle\Entity\BikeTraderRepository;
use SoftwareDesk\BikeTraderAPIBundle\Entity\BikeTraderRepositoryInterface;

use SoftwareDesk\BikeTraderAPIBundle\Utils\ConvertingEntityToObject;

/*
NEXT STEPS:
1  - Generate the Entities           (DONE)
2  - Set up Tables in DB             (DONE)
3  - Add some test values to them.   (DONE)
4  - Set up Custom Repository        (DONE)
5  - Hook up the code                (DONE)
6  - Review design.                  (DONE)
7  - Put in Repository Interface     (DONE)
8  - Remove ORM Dependency           (DONE)
9  - Review Manager Registry         (DONE)
10  - Write Test Case                (DONE)
11 - Read up Doctrine Fixtures       (DONE)
*/
/*
NEXT STEPS: WEEK STARTING 26TH JANUARY
1  - Unit Tests                                                (DONE)
2  - Functional Tests                                          (DONE)
3  - Fixtures                                                  (DONE)
4  - MongoDB                                                   (DONE)
5  - Set up other REST methods                                 (DONE)
6  - Redesign code and out in Manager Registry code            (DONE)
7  - Set up Tests (unit & functional)
8  - Documentation bundle
9  - Build as its own bundle
10 - Setup on Github and Packagelist
11 - Write up some notes on previous works (REST , GIT, etc)

-------------------------------------------------------------------------------
Going to put in the remaining rest methods and then redesign the code flow .
Then look at the manager registry
Then look at some basic testing.
Then Documentation bundle
Then design first draft of standalone bundle and research into open source flow.

-------------------------------------------------------------------------------

*** FURTHER DEVELOPMENTS -  AFTER OTHER BASIC REST METHODS ARE IN PLACE: ***
DESIGN NEW DB WITH JUST A FEW TABLES
DO SCHEMA DESIGN OF IT ALSO WITH ONLINE TOOL: http://dbdsgnr.appspot.com/
PUT THE DOCTRINE ORM CUSTOM REPOSITORY AND INTERFACE IN THE REPOSITORY FOLDER ALSO
DO UP XML STORAGE MAPPINGS FOR MODEL MAPPING INDEPENDENCE ? (might do as separate bundle due to xml)
DO UP NEW SERVICES USING SERVICE TAGS AND COMPILER PASS AND CONFIGURATION - think of scenarios.
*/


class BikeTraderManager extends BaseBikeTraderManager
{

    // private $entityManager;
    private $bikeTraderRepository;

    private $bikeEntityToObjectConverter;



    /*public function __construct( EntityManager $entityManager )
    {
        $this -> entityManager = $entityManager;
    }*/
    /*public function __construct(BikeTraderRepository $bikeTraderRepository)
    {
        $this -> bikeTraderRepository = $bikeTraderRepository;
    }*/
    // ***NOTE*** BY USING THE INTERFACE WE REMOVE THE COUPLING TO DOCTRINE ORM WHICH THE
    // BikeTraderRepository CLASS ABOVE IS TIGHTLY COUPLED TO...
    public function __construct(BikeTraderRepositoryInterface $bikeTraderRepository, ConvertingEntityToObject $convertingEntityToObject)
    {
        $this -> bikeTraderRepository = $bikeTraderRepository;
        $this -> bikeEntityToObjectConverter = $convertingEntityToObject;
    }


    // *** NOTE: *** This is the one method we will implement for demo purposes...
    // Assuming simple case with retrieval options for now...
    public function retrieveBike( array $retrievalOptions )
    {

echo PHP_EOL.PHP_EOL.'-- Now in the RetrieveBike method ---'.PHP_EOL.PHP_EOL;

        // Example of $retrievalOptions - array('type', $type)
        if ( is_array($retrievalOptions) ) {

            $retrievalMethod =  ucfirst($retrievalOptions[0]);
            $retrievalParam = $retrievalOptions[1];

            // Dynamically call the concrete retrieval method, e.g. findBikeByType()
            $retrievalHandler = 'findBikeBy'.$retrievalMethod;
            return $this -> $retrievalHandler($retrievalParam);

        }
    }


    public function findBikeByType($type)
    {
        $bike = $this -> bikeTraderRepository -> findBikeByType($type);
        return $bike;

    }

    public function findBikeById($id)
    {
        $bike = $this -> bikeTraderRepository -> findBikeById($id);
        return $bike;
    }

    public function clearTheEntityManager()
    {
        $this -> bikeTraderRepository -> clearTheEntityManager();
    }

    public function getLastModifiedTimestampForBike($id)
    {
        $timestamp = $this -> bikeTraderRepository -> getLastModifiedTimestampForBike($id);
        return $timestamp;
    }


    public function getEntityClassInstance()
    {
        $bikeClass = $this -> bikeTraderRepository -> getClassName();
        return new $bikeClass();
    }


    // *** NOTE ***
    // THE USE OF CREATE WILL BRING INTO PLAY THE USE OF A MANAGER REGISTRY TO AVOID
    // BEEN TIED TO A SPECIFIC ENTITY_MANAGER (AS getDoctrine() -> getManager() WILL
    // RETURN THE DEFAULT_ENTITY_MANAGER...
    // http://php-and-symfony.matthiasnoback.nl/2014/05/inject-the-manager-registry-instead-of-the-entity-manager/
    public function createBike($bike)
    {
            try {
                $this -> bikeTraderRepository -> save($bike);
            } catch( \Exception $e ) {
                /*
                 * To avoid an exception being thrown from a failed insert which would provide
                 * details of the database in the message. A more detailed message could be logged
                 * internally here as if this is executed it means the validation that is applied
                 * through submitting the data through the $form has failed.
                 */
                throw new HttpException(400, 'The resource could not be created');
            }
    }


    public function getExistingBikeById($id)
    {
        return $this -> bikeTraderRepository -> findBikeById($id);
    }


    /**
     * @param $id
     * @param array $parameterArrayOfUpdates
     * @return array
     *
     * This uses the $id value to retrieve an existing bike. Then
     * it converts this bike entity to a standard object.
     * The array of values passed in from the API are merged with an
     * array representing the bike object.
     * The final array contains all the updated value for the bike.
     */
    public function organiseArrayToBeUpdated($id, array $parameterArrayOfUpdates)
    {
        $existingBike = $this -> getExistingBikeById($id);
        // Converting the bike entity to a standard object.
        $bikeEntityConvertedToObject = $this -> bikeEntityToObjectConverter -> convertEntityToObject($existingBike);
        $updatedArray = array_merge( get_object_vars($bikeEntityConvertedToObject), $parameterArrayOfUpdates );

        // Must remove the primary key before submitting the form data array.
        $primaryKey = $this -> bikeTraderRepository -> getPrimaryKeyForEntity( $existingBike );
        unset($updatedArray[$primaryKey]);

        return $updatedArray;
    }




    public function updateBike()
    {
        $this -> bikeTraderRepository -> update();
    }


    public function deleteBike($id)
    {
        $bike = $this -> bikeTraderRepository -> findBikeById($id);
        if (is_null($bike)) {
            throw new ResourceNotFoundException('No such bike exists');
        }

        $this -> bikeTraderRepository -> delete($bike);

    }


} 