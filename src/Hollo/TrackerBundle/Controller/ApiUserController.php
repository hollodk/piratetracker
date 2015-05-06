<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/user")
 */
class ApiUserController extends Controller
{
    /**
     * @Route("/{id}/get")
     * @Method("GET")
     */
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->find('HolloTrackerBundle:User', $id);

        $res = new \stdClass();
        $res->id = $user->getId();
        $res->rank = $user->getRank();
        $res->name = $user->getName();
        $res->fraction = $user->getFraction()->getId();
        $res->username = $user->getUsername();
        $res->timestamp = $user->getCreatedAt()->format('Y-m-d H:i:s');

        $response = new Response(json_encode($res));
        return $response;
    }
}
