<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/20/15
 * Time: 12:15 PM
 */

namespace SoftwareDesk\BikeTraderAPIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\View\RouteRedirectView,
    FOS\RestBundle\View\RedirectView,
    FOS\RestBundle\View\View as FOSView;

use FOS\RestBundle\Request\ParamFetcher,
    FOS\RestBundle\Request\ParamFetcherInterface;

use FOS\RestBundle\Controller\Annotations\Route,
    FOS\RestBundle\Controller\Annotations\Prefix,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Controller\Annotations\RequestParam,
    FOS\RestBundle\Controller\Annotations\View;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException,
    Symfony\Component\HttpKernel\Exception\HttpException,
    Symfony\Component\Routing\Exception\ResourceNotFoundException,
    Symfony\Component\HttpFoundation\Request;

use SoftwareDesk\BikeTraderAPIBundle\Entity\Bicycle;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


/**
 * Class BikeTraderController
 * @package SoftwareDesk\BikeTraderAPIBundle\Controller
 *
 * Example URL:
 * http://symfonyclassroom/app_dev.php/api/v1/trader/bike/type/road
 */
class BikeTraderController extends FOSRestController
{

    /**
     * @param $type
     *
     * @Route("/bike/type/{type}")
     *
     * @return FOSView
     */
    public function getBikeTypeAction($type)
    {
        // Call the Bike Trader Manager (a service)
        $bikeTraderManager = $this -> get('software_desk_bike_trader_api.bike_trader_manager');

        $bike = $bikeTraderManager -> retrieveBike( array('type', $type) );

        if (empty($bike)) {
            throw new ResourceNotFoundException('No such bike exists');
        }

        $view = $this -> view($bike, 200);
        return $this -> handleView($view);
    }

/*

GET CURMPL SAMPLE COMMAND...
curl -v http://symfonyclassroom/app_dev.php/api/v1/trader/bike/type/road


POST CURL SAMPLE COMMAND...

curl -H "Content-Type: application/json"
-d '{"name":"bikename","description":"postdescription","type":"road"}'
http://symfonyclassroom/app_dev.php/api/v1/trader/bikes

PUT CURL SAMPLE COMMAND...

curl -H "Content-Type: application/x-www-form-urlencoded" -X PUT
-d name=updatedname -d description=udpateddescription
http://symfonyclassroom/app_dev.php/api/v1/trader/bikes/6

DELETE CURL SAMPLE COMMAND...

curl -X DELETE http://symfonyclassroom/app_dev.php/api/v1/trader/bikes/6

*/


    /**
     * Creates a new bike entry
     *
     * @param ParamFetcher $paramFetcher ParamFetcher
     *
     * @RequestParam(name="name", requirements="[a-z]+", default="", description="Bike name")
     * @RequestParam(name="description", requirements="[a-z]+", default="", description="Bike description")
     * @RequestParam(name="type", requirements="[a-z]+", default="", description="Bike type")
     *
     * @return FOSView
     */
    public function postBikeAction(ParamFetcher $paramFetcher)
    {
        // Call the Bike Trader Manager service.
        $bikeTraderManager = $this -> get('software_desk_bike_trader_api.bike_trader_manager');
        $bike = $bikeTraderManager -> getEntityClassInstance();

        /**
         * The instance is injected as a parameter to the form type service here
         * so it can be dynamic as it is different depending on the Repository that
         * is injected into the 'software_desk_bike_trader_api.bike_trader_manager' above.
         * That is done in the services file.
         * We could also put the type of bike instance (ORM or ODM) into the form type
         * service in the service file but as the repository value is changed to swap out
         * the backend storage, then we use that programmatically above.
         */
        $form = $this -> createForm('createBike', $bike);
        $form -> submit($paramFetcher -> all());

        /**
         * When the form is submitted, the key thing to understand here is that the submitted
         * data is transferred to the underlying object ($bike) immediately.
         * When you want to persist the data you need to then persist the object itself ($bike),
         * which already contains the submitted data thanks to $form -> submit().
         */
        if ( $form -> isValid() ) {
            try {

                $bikeTraderManager -> createBike($form -> getData());

            } catch (\Exception $e) {
                throw new HttpException(400, 'The resource could not be created');
            }

            $array = array('code' => 201, 'message' => 'The bike has been successfully created');
            $view = $this -> view($array, 201);
            return $this->handleView($view);

        } else {
            throw new HttpException(400, 'There was a problem with the posted data');
        }
    }





    /**
     * Update an existing bike entry
     * /api/v1/trader/bikes/{id}.{_format}
     *
     * @param Request $request - Current request
     *
     * @param string $id - Id of the bike to be updated
     *
     * @return FOSView
     */
    public function putBikeAction(Request $request, $id)
    {
        /*
         * NOTE: For the PUT method to work, the client request must send the
         *       the content type in:
         *       "Content-Type: application/x-www-form-urlencoded" format.
         */

        $requestParameterBagIterator = $request -> request -> getIterator();
        $parameterArrayOfUpdates = iterator_to_array($requestParameterBagIterator);

        // Call the Bike Trader Manager service.
        $bikeTraderManager = $this -> get('software_desk_bike_trader_api.bike_trader_manager');
        $updatedArray = $bikeTraderManager -> organiseArrayToBeUpdated($id, $parameterArrayOfUpdates);

        $bike = $bikeTraderManager -> getExistingBikeById($id);
        $form = $this -> createForm('createBike', $bike);

        $form -> submit($updatedArray);
        if ($form -> isValid()) {

            $bikeTraderManager -> updateBike();

            $array = array('code' => 200);
            $view = $this -> view($array);
            return $this -> handleView($view);
        } else {
            throw new HttpException(400, 'There was a problem with the data to be updated.');
        }


    }



    /**
     * Delete an existing bike entry
     * /api/v1/trader/bikes/{id}.{_format}
     *
     * @param $id
     *
     * // Route(requirements={"id"="\d+"})
     * @Route(requirements={"id"="[a-z0-9]+"})
     * @View(statusCode=204)
     */
    public function deleteBikeAction($id)
    {
        // Call the Bike Trader Manager service.
        $bikeTraderManager = $this -> get('software_desk_bike_trader_api.bike_trader_manager');
        $bikeTraderManager -> deleteBike($id);

    }


// ****** NEW CODE FOR IMPLEMENTING DEMONSTRATION CACHING MODELS ****

// GET CURMPL SAMPLE COMMAND...
//  curl -v http://symfonyclassroom/app_dev.php/api/v1/trader/cache/bike/type/road
// FOR PRODUCTION CAN USE:
// curl -v http://symfonyclassroom/api/v1/trader/cache/bike/type/road

/*
 * Set up the Symfony Reverse Proxy and the above cache response headers
 * that are set will start to come into effect.
 */

    /**
     * @param Request $request - Current request
     * @param $type
     *
     * @Route("/cache/bike/type/{type}")
     *
     * @return FOSView
     */
    public function getCacheBikeTypeAction(Request $request, $type)
    {

        // ***NOTE*** - This is a sample using the EXPIRATION CACHE Model.

        // Call the Bike Trader Manager (a service)
        $bikeTraderManager = $this -> get('software_desk_bike_trader_api.bike_trader_manager');
        $bike = $bikeTraderManager -> retrieveBike( array('type', $type) );

        if (empty($bike)) {
            throw new ResourceNotFoundException('No such bike exists');
        }

        $view = $this -> view($bike, 200);

        //$view -> getResponse() -> setMaxAge(600);
        //$view -> getResponse() -> setPublic();
        // Or it can be written this way also
        $view -> getResponse() -> setCache( array( 'max_age' => '200', 'public' => true ) );

        return $this -> handleView($view);
    }





    /**
     * @param Request $request - Current request
     * @param $id
     *
     * @Route("/cache/validation/bike/{id}")
     *
     * @return FOSView
     */
    public function getCacheValidationBikeTypeAction(Request $request, $id)
    {
        // Call the Bike Trader Manager (a service)
        $bikeTraderManager = $this -> get('software_desk_bike_trader_api.bike_trader_manager');

        $timestamp = $bikeTraderManager -> getLastModifiedTimestampForBike($id);

        $view = $this -> view();
        $view -> getResponse() -> setPublic();

        $lastModified = new \DateTime();
        $lastModified -> setTimestamp($timestamp);

        $view -> getResponse() -> setLastModified($lastModified);
        $view -> getResponse() -> setPublic();

        if ( $view -> getResponse() -> isNotModified($request) ) {
            echo PHP_EOL.'---IN THE REQUEST NOT MODIFIED CONDITION'.PHP_EOL;
            return $this -> handleView($view);
        }


        $bikeTraderManager -> clearTheEntityManager();
        $bike = $bikeTraderManager -> retrieveBike( array('id', $id) );

        if (empty($bike)) {
            throw new ResourceNotFoundException('No such bike exists');
        }

        $view -> setData($bike);
        $view -> setStatusCode(200);
        return $this -> handleView($view);
    }



    /**
     * @param Request $request - Current request
     * @param $id
     *
     * @Route("/cache/expiration/validation/bike/{id}")
     *
     * @return FOSView
     */
    public function getCacheExpirationValidationBikeTypeAction(Request $request, $id)
    {

        /**
         * This combines the Expiration and Validation models to produce
         * the usual foundation of HTTP Caching models.
         * Expiration will take priority as usual with the validation headers been
         * set each time the expiration has expired. If the validation header has
         * not changed since the last request then a 304 not modified response is
         * returned immediately and the cached value served up.
         */

        // Call the Bike Trader Manager (a service)
        $bikeTraderManager = $this -> get('software_desk_bike_trader_api.bike_trader_manager');

        $timestamp = $bikeTraderManager -> getLastModifiedTimestampForBike($id);

        $view = $this -> view();
        $view -> getResponse() -> setPublic();

        // Set the timestamp into DateTime format for the HTTP Response header.
        $lastModified = new \DateTime();
        $lastModified -> setTimestamp($timestamp);

        $view -> getResponse() -> setLastModified($lastModified);
        $view -> getResponse() -> setMaxAge(60);
        $view -> getResponse() -> setPublic();

        if ( $view -> getResponse() -> isNotModified($request) ) {
            echo PHP_EOL.'---IN THE REQUEST NOT MODIFIED CONDITION'.PHP_EOL;
            return $this -> handleView($view);
        }

        $bikeTraderManager -> clearTheEntityManager();
        $bike = $bikeTraderManager -> retrieveBike( array('id', $id) );
        if (empty($bike)) {
            throw new ResourceNotFoundException('No such bike exists');
        }

        $view -> setData($bike);
        $view -> setStatusCode(200);
        return $this -> handleView($view);
    }


// SAME CURL COMMANDS TO EXECUTE THE REST API...

// curl -H "Content-Type: application/x-www-form-urlencoded" -X PUT
// -d name=updatedname -d description=udpateddescription
// http://symfonyclassroom/app_dev.php/api/v1/trader/bikes/6

// curl -H "Content-Type: application/x-www-form-urlencoded" -X PUT -d name=updatedname -d description=udpateddescription http://symfonyclassroom/app_dev.php/api/v1/trader/bikes/6

// curl -X DELETE http://symfonyclassroom/app_dev.php/api/v1/trader/bikes/6

// FOR GET PRODUCTION CAN USE:
// curl -v http://symfonyclassroom/api/v1/trader/cache/bike/type/road

// 54e1e0c5f889de862b8b4567
// curl -H "Content-Type: application/x-www-form-urlencoded" -X PUT -d name=updatedname -d description=udpateddescription http://symfonyclassroom/app_dev.php/api/v1/trader/bikes/54e1e0c5f889de862b8b4567

// POST CURL SAMPLE COMMAND...

// curl -H "Content-Type: application/json" -d '{"name":"bikename","description":"postdescription","type":"road"}' http://symfonyclassroom/app_dev.php/api/v1/trader/bikes


}