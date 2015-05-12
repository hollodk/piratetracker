<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/generel")
 */
class ApiGenerelController extends Controller
{
    /**
     * @Route("/first-route")
     * @Method("GET")
     */
    public function firstrouteAction()
    {
        $res = $this->get('hollo_tracker.coordinate')->getFirstRoute();

        $response = new Response(json_encode($res));
        return $response;
    }

    /**
     * @Route("/second-route")
     * @Method("GET")
     */
    public function secondrouteAction()
    {
        $res = $this->get('hollo_tracker.coordinate')->getSecondRoute();

        $response = new Response(json_encode($res));
        return $response;
    }
}
