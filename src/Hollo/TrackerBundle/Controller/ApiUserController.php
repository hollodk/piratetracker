<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

        if ($user->getProfileImage()) {
            $res->profile_image = $user->getProfileImage();
        }

        $response = new Response(json_encode($res));
        return $response;
    }

    /**
     * @Route("/update")
     * @Method({"PUT", "POST"})
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();

        if ($request->files->get('profile_image') != null) {
            $user->setProfileImage(base64_encode(file_get_contents($request->files->get('profile_image')->getPathName())));
        }

        if (strlen($request->get('name')) > 0) {
            $user->setName($request->get('name'));
        }

        if (strlen($request->get('username')) > 0) {
            $user->setUsername($request->get('username'));
        }

        if (strlen($request->get('password')) > 0) {
            $user->setPassword($request->get('password'));
        }

        if (strlen($request->get('email')) > 0) {
            $user->setEmail($request->get('email'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $response = new Response('ok');
        return $response;
    }
}
