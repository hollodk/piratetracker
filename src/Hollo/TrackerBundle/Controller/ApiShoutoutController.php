<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\ShoutOut;

class ApiShoutoutController extends Controller
{
    /**
     * @Route("/shoutout")
     * @Route("/api/shoutout")
     * @Method({"PUT", "POST"})
     */
    public function postAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new ShoutOut;
        $entity->setType('public');
        $entity->setContent($request->get('content'));
        $entity->setLatitude($request->get('latitude'));
        $entity->setLongitude($request->get('longitude'));
        $entity->setUser($this->getUser());

        $em->persist($entity);
        $em->flush();

        $response = new Response('ok');
        return $response;
    }

    /**
     * @Route("/shoutout/get/all")
     * @Route("/api/shoutout/get/all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('HolloTrackerBundle:ShoutOut')->findBy(
            array(),
            array('id' => 'DESC')
        );

        $res = array();
        foreach ($entities as $entity) {
            $r = new \stdClass();
            $r->id = $entity->getId();
            $r->user = $entity->getUser()->getId();
            $r->content = $entity->getContent();
            $r->latitude = $entity->getLatitude();
            $r->longitude = $entity->getLongitude();
            $r->timestamp = $entity->getCreatedAt()->format('Y-m-d H:i:s');

            $res[] = $r;
        }

        $json = json_encode($res);

        $response = new Response($json);
        return $response;
    }
}
