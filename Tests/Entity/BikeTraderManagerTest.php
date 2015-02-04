<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/26/15
 * Time: 3:17 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Tests\Entity;

use MyProject\Proxies\__CG__\stdClass;
use SoftwareDesk\BikeTraderAPIBundle\Entity\BikeTraderManager;
use SoftwareDesk\BikeTraderAPIBundle\Entity\Bicycle;

class BikeTraderManagerTest extends \PHPUnit_Framework_TestCase
{

    const EXCEPTION_MISSING_ARGUMENT = '/Missing argument/';

    protected $bikeTraderRepositoryMock;
    protected $bikeMock;
    protected $bikeTraderManager;

    /*
     * Setting up Bicycle and BikeTraderRepository Mocks as the
     * BikeTraderManager has a dependency on the BikeTraderRepository which
     * itself returns a Bicycle entity.
     */
    protected function setUp()
    {

        $this -> bikeMock = $this -> getMockBuilder('SoftwareDesk\BikeTraderAPIBundle\Entity\Bicycle')
            -> disableOriginalConstructor()
            -> getMock();
        // print_r($this -> bikeMock);

        $this -> bikeTraderRepositoryMock = $this -> getMockBuilder('SoftwareDesk\BikeTraderAPIBundle\Entity\DoctrineORMBikeTraderRepository')
            -> disableOriginalConstructor()
            -> setMethods( array('findBikeByType') )
            -> getMock();
        // print_r($this -> bikeTraderRepositoryMock);

        /*
         * This sets up the repository mock method findBikeByType to return
         * a mock Bicycle entity so to match the expected behaviour the
         * findBikeByType method in the BikeTraderManager expects.
         */
        $this -> bikeTraderRepositoryMock -> expects($this -> any())
            -> method('findBikeByType')
            -> will( $this -> returnValue( $this -> bikeMock ) );

        $this -> bikeTraderManager = new BikeTraderManager( $this -> bikeTraderRepositoryMock );
    }



    public function testFindBikeByType()
    {
        // The findBikeByType() method takes a string $type parameter, e.g. road
        $type = 'road';

        // Testing the expected return type of a Bicycle, which is a mock here.
        $this -> assertInstanceOf(
            'SoftwareDesk\BikeTraderAPIBundle\Entity\Bicycle',
            $this -> bikeTraderManager -> findBikeByType($type)
        );
    }



    // Exception Tests

    /*
     * Test that the call to BikeTraderManager::findBikeByType() without
     * the $type parameter will fail.
     */
    public function testFindBikeByTypeThrowsExceptionMissingArgument()
    {
        try {
            $bike = $this -> bikeTraderManager -> findBikeByType();
        } catch ( \Exception $e ) {
            return $this -> assertRegExp( self::EXCEPTION_MISSING_ARGUMENT, $e -> getMessage() );
        }

        $this->fail( 'An Exception should be thrown if the BikeTraderManager::findBikeByType method is not passed a $type parameter' );
    }

} 