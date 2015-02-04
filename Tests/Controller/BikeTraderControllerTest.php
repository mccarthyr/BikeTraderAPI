<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/23/15
 * Time: 6:33 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BikeTraderControllerTest
 * @package SoftwareDesk\BikeTraderAPIBundle\Tests\Controller
 *
 * Functional Test
 */
class BikeTraderControllerTest extends WebTestCase
{
    /*
     * Example url:
     * http://symfonyclassroom/app_dev.php/api/v1/trader/bike/type/road
     */
    public function testGetBikeType()
    {
        $client = static::createClient();
        $crawler = $client -> request('GET','/api/v1/trader/bike/type/road' );

        // This will print out the returned JSON content...
        echo $client -> getResponse() -> getContent();
        /* Example of the outputted response content:
           [{"name":"Prestigio","description":"Italian custom made bike","type":"road","id":1},
           {"name":"Giant","description":"American road racing bike","type":"road","id":2}]
        */

        // Assert that the "Content-Type" header is "application/json"
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ));

        // Assert that the content of the response contains the word 'road'
        $this -> assertRegExp( '/road/', $client -> getResponse() -> getContent() );

        // Assert that the response status code is 2xx
        $this -> assertTrue( $client -> getResponse() -> isSuccessful() );

        // Assert a specific 200 status code
        $this -> assertEquals( Response::HTTP_OK, $client -> getResponse() -> getStatusCode() );

    }

/* *** NOTE ***
THIS WILL GENERATE THE HTML COVERAGE DOCUMENTS WHEN RUNNING THE TESTS...
phpunit --coverage-html=cov/ -c app/ src/SoftwareDesk/

TO VIEW IN THE BROWSER:
file:///var/www/vhosts/symfonyClassroom/cov/index.html
*/


}




