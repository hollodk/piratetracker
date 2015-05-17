<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\Position;

class ApiFractionController extends Controller
{
    /**
     * @Route("/fraction/get/all")
     * @Route("/api/fraction/get/all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();

        $res = array();
        foreach ($entities as $entity) {
            $r = new \stdClass();
            $r->id = $entity->getId();
            $r->name = $entity->getName();

            $res[] = $r;
        }

        $json = json_encode($res);

        $response = new Response($json);
        return $response;
    }
}
