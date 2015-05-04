<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/position")
 */
class ApiPositionController extends Controller
{
    /**
     * @Route("")
     * @Method("PUT")
     */
    public function updateAction(Request $request)
    {
        $lat = $request->get('lat');
        $lon = $request->get('lon');

        $response = new Response($json);
        return $response;
    }

    /**
     * @Route("/get")
     * @Method("GET")
     */
    public function getAction()
    {
        $json = '{"id":"4","latitude":"42.0000000","longitude":"42.0000000","user":"1","timestamp":"2015-04-14 18:03:36","created_at":"0000-00-00 00:00:00","updated_at":"0000-00-00 00:00:00"}';

        $response = new Response($json);
        return $response;
    }

    /**
     * @Route("/get/all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        $json = '[{"id":"4","latitude":"42.0000000","longitude":"42.0000000","user":"1","timestamp":"2015-04-14 18:03:36","created_at":"0000-00-00 00:00:00","updated_at":"0000-00-00 00:00:00"},{"id":"3","latitude":"12.0000000","longitude":"33.0000000","user":"1","timestamp":"2015-01-12 21:07:12","created_at":"0000-00-00 00:00:00","updated_at":"0000-00-00 00:00:00"},{"id":"2","latitude":"1000.0000000","longitude":"134.0000000","user":"1","timestamp":"2015-01-11 13:58:39","created_at":"0000-00-00 00:00:00","updated_at":"0000-00-00 00:00:00"},{"id":"1","latitude":"12.0000000","longitude":"33.0000000","user":"1","timestamp":"2015-01-11 13:58:32","created_at":"0000-00-00 00:00:00","updated_at":"0000-00-00 00:00:00"}]';

        $response = new Response($json);
        return $response;
    }
}
