<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/position")
 */
class PositionController extends Controller
{
    /**
     * @Route("/get")
     */
    public function getAction()
    {
        $response = new Response;

        return $response;
    }
}
