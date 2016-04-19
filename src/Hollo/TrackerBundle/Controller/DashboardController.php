<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\GroundOverlay;
use Ivory\GoogleMap\Overlays\InfoWindow;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\MarkerImage;
use Ivory\GoogleMap\Overlays\Polyline;
use Hollo\TrackerBundle\Entity\Fraction;
use Hollo\TrackerBundle\Entity\Position;
use Hollo\TrackerBundle\Entity\User;
use Hollo\TrackerBundle\Entity\ShoutOut;
use Hollo\TrackerBundle\Form\FractionType;

/**
 * Fraction controller.
 */
class DashboardController extends Controller
{
    /**
     * @Route("")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $this->baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $em = $this->getDoctrine()->getManager();

        $center = null;
        $zoom = 14;
        $r = $em->getRepository('HolloTrackerBundle:User')->findOneByMapFollow(true);
        if ($r && $r->getPosition()) {
            $center = array(
                'lat' => $r->getPosition()->getLatitude(),
                'lon' => $r->getPosition()->getLongitude()
            );
            $zoom = 15;
        }

        $map = $this->getMap($center, $zoom);

        $fractions = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();
        $shouts = $em->getRepository('HolloTrackerBundle:ShoutOut')->findBy(
            array(),
            array('id' => 'DESC'),
            30
        );

        $entities = $em->getRepository('HolloTrackerBundle:User')->findAll();

        foreach ($entities as $entity) {
            if ($entity->getPosition()) {
                $this->addUserMarker($entity, $map);
            }
        }

        $entities = $em->getRepository('HolloTrackerBundle:ShoutOut')->getRecent();

        foreach ($entities as $entity) {
            $this->addShoutMarker($entity, $map);
        }

        return array(
            'map' => $map,
            'fractions' => $fractions,
            'shouts' => $shouts
        );
    }

    /**
     * @Route("/{id}/track")
     * @Template("HolloTrackerBundle:Dashboard:index.html.twig")
     */
    public function trackAction(Request $request, User $entity)
    {
        $this->baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $center = null;
        if ($entity->getPosition()) {
            $center = array(
                'lat' => $entity->getPosition()->getLatitude(),
                'lon' => $entity->getPosition()->getLongitude()
            );
        }

        $map = $this->getMap($center, 16);

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('HolloTrackerBundle:Position')->getRecent($entity);

        $fractions = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();
        $shouts = $em->getRepository('HolloTrackerBundle:ShoutOut')->findBy(
            array(),
            array('id' => 'DESC'),
            30
        );

        if (count($entities) > 0) {
            $polyline = new Polyline();
            $polyline->setOption('strokeColor', '#ff8a00');

            foreach ($entities as $position) {
                $polyline->addCoordinate($position->getLatitude(), $position->getLongitude());
            }

            $map->addPolyline($polyline);
        }

        if ($entity->getPosition()) {
            $this->addUserMarker($entity, $map);
        }

        return array(
            'map' => $map,
            'fractions' => $fractions,
            'currentUser' => $entity,
            'shouts' => $shouts
        );
    }

    private function getMap($center = null, $zoom = 14)
    {
        if ($center == null) {
            $center = array(
                'lat' => 57.0445,
                'lon' => 9.93
            );
        }

        $map = $this->get('ivory_google_map.map');
        $map->setCenter($center['lat'], $center['lon'], true);
        $map->setMapOption('zoom', $zoom);
        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '600px');

        $this->setFirstRoute($map);
        $this->setSecondRoute($map);

        $this->addPirateImage($map);
        $this->addFishImage($map);
        $this->addIslandImage($map);
        $this->addOctopusImage($map);
        $this->addWormImage($map);
        $this->addPartyImage($map);

        return $map;
    }

    private function addShoutMarker(ShoutOut $shout, $map)
    {
        $position = new Position();
        $position->setLongitude($shout->getLongitude());
        $position->setLatitude($shout->getLatitude());

        $marker = $this->buildShoutMarker($position, $shout);
        $map->addMarker($marker);
    }

    private function addUserMarker(User $entity, $map)
    {
        $position = $entity->getPosition();
        $fraction = $entity->getFraction();

        $em = $this->getDoctrine()->getManager();
        $shout = $em->getRepository('HolloTrackerBundle:ShoutOut')->getLatest($entity);

        $marker = $this->buildMarker($position, $fraction, $entity, $shout);
        $map->addMarker($marker);
    }

    private function buildShoutMarker(Position $position, ShoutOut $shout=null)
    {
        $marker = new Marker();
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($position->getLatitude(), $position->getLongitude(), true);

        $bounce = false;

        if ($shout) {
            $diff = $shout->getCreatedAt()->diff(new \DateTime());

            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            if ($minutes < 20) {
                $bounce = true;
            }
        }

        if ($bounce) {
            $marker->setAnimation(Animation::BOUNCE);
        } else {
            $marker->setAnimation(Animation::DROP);
        }

        $base = '/bundles/hollotracker/images/';
        $icon = 'marker-note.png';

        $marker->setIcon(sprintf('%s/%s/%s',
            $this->baseurl,
            $base,
            $icon
        ));

        $infoWindow = new InfoWindow();

        $infoWindow->setPrefixJavascriptVariable('info_window_');
        $infoWindow->setContent($this->renderView('HolloTrackerBundle:Dashboard:shoutbox.html.twig', array(
            'user' => $shout->getUser(),
            'shout' => $shout
        )));
        $infoWindow->setAutoClose(true);

        $marker->setInfoWindow($infoWindow);

        return $marker;
    }

    private function buildMarker(Position $position, Fraction $fraction=null, User $user, ShoutOut $shout=null)
    {
        $marker = new Marker();
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($position->getLatitude(), $position->getLongitude(), true);

        $bounce = false;

        /*
        if ($shout) {
            $diff = $shout->getCreatedAt()->diff(new \DateTime());

            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            if ($minutes < 20) {
                $bounce = true;
            }
        }

        if ($bounce) {
            $marker->setAnimation(Animation::BOUNCE);
        } else {
            $marker->setAnimation(Animation::DROP);
        }
         */
        $marker->setAnimation(Animation::DROP);

        $base = '/bundles/hollotracker/images/';
        $icon = 'marker-grey-small.png';

        if (strlen($user->getIcon()) > 0) {
            $icon = $user->getIcon();
        } elseif ($fraction && strlen($fraction->getIcon()) > 0) {
            $icon = $fraction->getIcon();
        }
        $marker->setIcon(sprintf('%s/%s/%s',
            $this->baseurl,
            $base,
            $icon
        ));

        $infoWindow = new InfoWindow();

        $infoWindow->setPrefixJavascriptVariable('info_window_');
        $infoWindow->setContent($this->renderView('HolloTrackerBundle:Dashboard:infobox.html.twig', array(
            'user' => $user,
            'shout' => $shout
        )));
        $infoWindow->setAutoClose(true);

        $marker->setInfoWindow($infoWindow);

        return $marker;
    }

    private function addPirateImage($map)
    {
        $groundOverlay = new GroundOverlay();
        $groundOverlay->setPrefixJavascriptVariable('ground_overlay_');
        $groundOverlay->setUrl($this->baseurl.'/bundles/hollotracker/images/skib_test.png');
        $groundOverlay->setBound(57.049883, 9.929269, 57.052672, 9.940661, true, true);

        $map->addGroundOverlay($groundOverlay);
    }

    private function addFishImage($map)
    {
        $groundOverlay = new GroundOverlay();
        $groundOverlay->setPrefixJavascriptVariable('ground_overlay_');
        $groundOverlay->setUrl($this->baseurl.'/bundles/hollotracker/images/fish.png');
        $groundOverlay->setBound(57.051962, 9.943909, 57.053956, 9.947576, true, true);

        $map->addGroundOverlay($groundOverlay);
    }

    private function addIslandImage($map)
    {
        $groundOverlay = new GroundOverlay();
        $groundOverlay->setPrefixJavascriptVariable('ground_overlay_');
        $groundOverlay->setUrl($this->baseurl.'/bundles/hollotracker/images/island.png');
        $groundOverlay->setBound(57.053298, 9.948091, 57.055758, 9.952948, true, true);

        $map->addGroundOverlay($groundOverlay);
    }

    private function addOctopusImage($map)
    {
        $groundOverlay = new GroundOverlay();
        $groundOverlay->setPrefixJavascriptVariable('ground_overlay_');
        $groundOverlay->setUrl($this->baseurl.'/bundles/hollotracker/images/octopus.png');
        $groundOverlay->setBound(57.055541, 9.912285, 57.057831, 9.917540, true, true);

        $map->addGroundOverlay($groundOverlay);
    }

    private function addWormImage($map)
    {
        $groundOverlay = new GroundOverlay();
        $groundOverlay->setPrefixJavascriptVariable('ground_overlay_');
        $groundOverlay->setUrl($this->baseurl.'/bundles/hollotracker/images/limworm.png');
        $groundOverlay->setBound(57.058159, 9.898183, 57.060999, 9.911512, true, true);

        $map->addGroundOverlay($groundOverlay);
    }

    private function addPartyImage($map)
    {
        $groundOverlay = new GroundOverlay();
        $groundOverlay->setPrefixJavascriptVariable('ground_overlay_');
        $groundOverlay->setUrl($this->baseurl.'/bundles/hollotracker/images/piragfest.png');
        $groundOverlay->setBound(57.038362, 9.900834, 57.041758, 9.910619, true, true);

        $map->addGroundOverlay($groundOverlay);
    }

    private function setFirstRoute($map)
    {
        $polyline = new Polyline();
        $polyline->setOption('strokeColor', '#303030');

        $route = $this->get('hollo_tracker.coordinate')->getFirstRoute();
        foreach ($route as $r) {
            $polyline->addCoordinate($r['lat'], $r['lon'], true);
        }

        $map->addPolyline($polyline);
    }

    private function setSecondRoute($map)
    {
        $polyline = new Polyline();
        $polyline->setOption('strokeColor', '#303030');

        $route = $this->get('hollo_tracker.coordinate')->getSecondRoute();
        foreach ($route as $r) {
            $polyline->addCoordinate($r['lat'], $r['lon'], true);
        }

        $map->addPolyline($polyline);
    }
}
