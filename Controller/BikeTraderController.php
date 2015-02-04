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



}