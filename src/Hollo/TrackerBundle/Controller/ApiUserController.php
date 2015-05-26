<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Hollo\TrackerBundle\Entity\Image;

class ApiUserController extends Controller
{
    /**
     * @Route("/user/{id}/get")
     * @Route("/api/user/{id}/get")
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
        $res->map_follow = $user->getMapFollow();
        $res->username = $user->getUsername();
        $res->timestamp = $user->getCreatedAt()->format('Y-m-d H:i:s');

        if ($user->getFraction()) {
            $res->fraction = $user->getFraction()->getId();
        }
        if ($user->getProfileImage()) {
            $res->profile_image_link = $this->generateUrl(
                'hollo_tracker_media_index', array(
                    'id' => $user->getId(),
                    'size' => 100
                ),
                true
            );
        }

        $response = new Response(json_encode($res));
        return $response;
    }

    /**
     * @Route("/user/update")
     * @Route("/api/user/update")
     * @Method({"PUT", "POST"})
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($request->files->get('profile_image') != null) {
            $user->setProfileImage(base64_encode(file_get_contents($request->files->get('profile_image')->getPathName())));

            $image = new Image();
            $image->setLatitude($request->get('latitude'));
            $image->setLongitude($request->get('longitude'));
            $image->setUser($this->getUser());
            $image->setImage($user->getProfileImage());

            $em->persist($image);
        }

        if ($request->get('profile_image_base64') != null) {
            $user->setProfileImage($request->get('profile_image_base64'));

            $image = new Image();
            $image->setUser($this->getUser());
            $image->setImage($user->getProfileImage());

            $em->persist($image);
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

        $em->flush();

        $response = new Response('ok');
        return $response;
    }
}
