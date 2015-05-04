<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\Position;

/**
 * @Route("/position")
 */
class ApiPositionController extends Controller
{
    /**
     * @Route("")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $position = new Position;
        $position->setLatitude($request->get('lat'));
        $position->setLongitude($request->get('lon'));

        $user = $em->find('HolloTrackerBundle:User', 1);

        $user->setPosition($position);
        $position->setUser($user);

        $em->persist($user);
        $em->flush();

        $response = new Response('ok');
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
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('HolloTrackerBundle:User')->findAll();

        $res = array();
        foreach ($users as $user) {
            $position = $user->getPosition();

            $r = new \stdClass();
            $r->id = $position->getId();
            $r->user = $user->getId();

            if ($position) {
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
