<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\Fraction;
use Hollo\TrackerBundle\Entity\Position;
use Hollo\TrackerBundle\Entity\User;
use Hollo\TrackerBundle\Entity\ShoutOut;
use Hollo\TrackerBundle\Form\FractionType;

/**
 * @Route("/ajax")
 */
class AjaxController extends Controller
{
    /**
     * @Route("/markers")
     * @Template()
     */
    public function markersAction(Request $request)
    {
        $this->baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $em = $this->getDoctrine()->getManager();

        $markers = array();
        $entities = $em->getRepository('HolloTrackerBundle:User')->findAll();
        foreach ($entities as $entity) {
            if ($entity->getPosition()) {
                $markers[] = $this->getUserMarker($entity);
            }
        }

        $entities = $em->getRepository('HolloTrackerBundle:ShoutOut')->getRecent();
        foreach ($entities as $entity) {
            $markers[] = $this->getShoutMarker($entity);
        }

        $response = new JsonResponse();
        $response->setData(array(
            'markers' => $markers
        ));

        return $response;
    }

    /**
     * @Route("/images")
     */
    public function imagesAction(Request $request)
    {
        $this->baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $images = $this->getImages();

        $response = new JsonResponse();
        $response->setData(array(
            'images' => $images
        ));

        return $response;
    }

    /**
     * @Route("/routes")
     */
    public function routesAction(Request $request)
    {
        $this->baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $routes = $this->getRoutes();

        if ($request->get('user')) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->find('HolloTrackerBundle:User', $request->get('user'));

            $routes[] = $this->getRouteByUser($user);
        }

        $response = new JsonResponse();
        $response->setData(array(
            'routes' => $routes
        ));

        return $response;
    }

    private function getShoutMarker(ShoutOut $shout)
    {
        $marker = new \StdClass();
        $marker->id = sprintf('10000%d', $shout->getId());
        $marker->type = 'shout';
        $marker->name = $shout->getUser()->getUsername();
        $marker->time = $shout->getCreatedAt()->format('H:i');

        $marker->coords = array(
            'lat' => $shout->getLatitude(),
            'lon' => $shout->getLongitude()
        );

        if ($shout) {
            $diff = $shout->getCreatedAt()->diff(new \DateTime());

            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            $marker->minutes_ago = $minutes;
        }

        $base = '/bundles/hollotracker/images/';

        if ($shout->getImage()) {
            $icon = 'marker-note-image.png';
        } else {
            $icon = 'marker-note.png';
        }

        $marker->icon = sprintf('%s/%s/%s',
            $this->baseurl,
            $base,
            $icon
        );

        $marker->infowindow = $this->renderView('HolloTrackerBundle:Dashboard:shoutbox.html.twig', array(
            'user' => $shout->getUser(),
            'shout' => $shout
        ));
        $marker->infowindow = preg_replace("/\n/", "", $marker->infowindow);

        return $marker;
    }

    private function getUserMarker(User $user)
    {
        $marker = new \StdClass();
        $marker->id = sprintf('%d', $user->getId());
        $marker->type = 'user';
        $marker->name = $user->getUsername();
        $marker->time = null;

        $position = $user->getPosition();
        $fraction = $user->getFraction();

        $em = $this->getDoctrine()->getManager();
        $shout = $em->getRepository('HolloTrackerBundle:ShoutOut')->getLatest($user);

        $marker->coords = array(
            'lat' => $position->getLatitude(),
            'lon' => $position->getLongitude()
        );

        $base = '/bundles/hollotracker/images/';
        $icon = 'marker-grey-small.png';

        $date = new \DateTime();
        $date->modify('-7 days');

        if ($position->getCreatedAt() > $date) {
            if (strlen($user->getIcon()) > 0) {
                $icon = $user->getIcon();
            } elseif ($fraction && strlen($fraction->getIcon()) > 0) {
                $icon = $fraction->getIcon();
            }

            $marker->time = $position->getCreatedAt()->format('H:i');
        }

        $marker->icon = sprintf('%s/%s/%s',
            $this->baseurl,
            $base,
            $icon
        );

        $marker->infowindow = $this->renderView('HolloTrackerBundle:Dashboard:infobox.html.twig', array(
            'user' => $user,
            'shout' => $shout
        ));
        $marker->infowindow = preg_replace("/\n/", "", $marker->infowindow);

        return $marker;
    }

    private function getImages()
    {
        $images = array();

        $images[] = $this->getPirateImage();
        $images[] = $this->getFishImage();
        $images[] = $this->getIslandImage();
        $images[] = $this->getOctopusImage();
        $images[] = $this->getWormImage();
        $images[] = $this->getPartyImage();

        return $images;
    }

    private function getPirateImage()
    {
        $res = new \StdClass();
        $res->url = $this->baseurl.'/bundles/hollotracker/images/skib_test.png';
        $res->coords = array(
            'x1' => 57.049883,
            'x2' => 9.929269,
            'y1' => 57.052672,
            'y2' => 9.940661
        );

        return $res;
    }

    private function getFishImage()
    {
        $res = new \StdClass();
        $res->url = $this->baseurl.'/bundles/hollotracker/images/fish.png';
        $res->coords = array(
            'x1' => 57.051962,
            'x2' => 9.943909,
            'y1' => 57.053956,
            'y2' => 9.947576
        );

        return $res;
    }

    private function getIslandImage()
    {
        $res = new \StdClass();
        $res->url = $this->baseurl.'/bundles/hollotracker/images/island.png';
        $res->coords = array(
            'x1' => 57.053298,
            'x2' => 9.948091,
            'y1' => 57.055758,
            'y2' => 9.952948
        );

        return $res;
    }

    private function getOctopusImage()
    {
        $res = new \StdClass();
        $res->url = $this->baseurl.'/bundles/hollotracker/images/octopus.png';
        $res->coords = array(
            'x1' => 57.055541,
            'x2' => 9.912285,
            'y1' => 57.057831,
            'y2' => 9.917540
        );

        return $res;
    }

    private function getWormImage()
    {
        $res = new \StdClass();
        $res->url = $this->baseurl.'/bundles/hollotracker/images/limworm.png';
        $res->coords = array(
            'x1' => 57.058159,
            'x2' => 9.898183,
            'y1' => 57.060999,
            'y2' => 9.911512
        );

        return $res;
    }

    private function getPartyImage()
    {
        $res = new \StdClass();
        $res->url = $this->baseurl.'/bundles/hollotracker/images/piragfest.png';
        $res->coords = array(
            'x1' => 57.038362,
            'x2' => 9.900834,
            'y1' => 57.041758,
            'y2' => 9.910619
        );

        return $res;
    }

    private function getRoutes()
    {
        $res = array();
        $res[] = $this->getFirstRoute();
        $res[] = $this->getSecondRoute();

        return $res;
    }

    private function getFirstRoute()
    {
        $res = new \StdClass();
        $res->color = '#303030';
        $res->coords = array();

        $route = $this->get('hollo_tracker.coordinate')->getFirstRoute();
        foreach ($route as $r) {
            $res->coords[] = $r;
        }

        return $res;
    }

    private function getSecondRoute()
    {
        $res = new \StdClass();
        $res->color = '#303030';
        $res->coords = array();

        $route = $this->get('hollo_tracker.coordinate')->getSecondRoute();
        foreach ($route as $r) {
            $res->coords[] = $r;
        }

        return $res;
    }

    private function getRouteByUser(User $user)
    {
        $res = new \StdClass();
        $res->color = '#ff8a00';
        $res->coords = array();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('HolloTrackerBundle:Position')->getRecent($user);

        if (count($entities) > 0) {
            foreach ($entities as $position) {
                $res->coords[] = array(
                    'lat' => $position->getLatitude(),
                    'lon' => $position->getLongitude()
                );
            }
        }

        return $res;
    }
}
