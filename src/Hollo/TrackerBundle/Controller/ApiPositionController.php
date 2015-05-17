<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\Position;

class ApiPositionController extends Controller
{
    /**
     * @Route("/position")
     * @Route("/api/position")
     * @Method({"PUT", "POST"})
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $position = new Position;
        $position->setLatitude($request->get('lat'));
        $position->setLongitude($request->get('lon'));

        $user = $em->find('HolloTrackerBundle:User', $this->getUser()->getId());

        $user->setPosition($position);
        $position->setUser($user);

        $em->persist($user);
        $em->persist($position);

        $em->flush();

        $response = new Response('ok');
        return $response;
    }

    /**
     * @Route("/position/get")
     * @Route("/api/position/get")
     * @Method("GET")
     */
    public function getAction()
    {
        $user = $this->getUser();
        $position = $user->getPosition();

        $r = new \stdClass();
        $r->user = $user->getId();

        if ($position) {
            $r->id = $position->getId();
            $r->latitude = $position->getLatitude();
            $r->longitude = $position->getLongitude();
            $r->timestamp = $position->getCreatedAt()->format('Y-m-d H:i:s');
        }

        $response = new Response(json_encode($r));
        return $response;
    }

    /**
     * @Route("/position/get/all")
     * @Route("/api/position/get/all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('HolloTrackerBundle:User')->findAll();

        $res = array();
        foreach ($users as $user) {
            $position = $user->getPosition();

            $r = new \stdClass();
            $r->user = $user->getId();

            if ($position) {
                $r->id = $position->getId();
                $r->latitude = $position->getLatitude();
                $r->longitude = $position->getLongitude();
                $r->timestamp = $position->getCreatedAt()->format('Y-m-d H:i:s');
            }

            $res[] = $r;
        }

        $json = json_encode($res);

        $response = new Response($json);
        return $response;
    }
}
