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

        $map = $this->getMap();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('HolloTrackerBundle:User')->findAll();
        $fractions = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();

        foreach ($entities as $entity) {
            $position = $entity->getPosition();

            if ($position) {
                $this->addMarker($position, $map);
            }
        }

        return array(
            'map' => $map,
            'fractions' => $fractions,
        );
    }

    /**
     * @Route("/{id}/track")
     * @Template("HolloTrackerBundle:Dashboard:index.html.twig")
     */
    public function trackAction(Request $request, User $entity)
    {
        $this->baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $map = $this->getMap();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('HolloTrackerBundle:Position')->findBy(
            array(
                'user' => $entity
            ),
            array(
                'id' => 'ASC'
            )
        );
        $fractions = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();

        if (count($entities) > 0) {
            $polyline = new Polyline();
            $polyline->setOption('strokeColor', '#0ff');

            foreach ($entities as $position) {
                $polyline->addCoordinate($position->getLatitude(), $position->getLongitude());
            }

            $map->addPolyline($polyline);
        }

        $position = $entity->getPosition();
        if ($position) {
            $this->addMarker($position, $map);
        }

        return array(
            'map' => $map,
            'fractions' => $fractions,
            'currentUser' => $entity
        );
    }

    private function getMap()
    {
        $map = $this->get('ivory_google_map.map');
        $map->setCenter(57.0445, 9.93, true);
        $map->setMapOption('zoom', 14);
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

    private function addMarker(Position $entity, $map)
    {
        $marker = new Marker();

        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($entity->getLatitude(), $entity->getLongitude(), true);
        $marker->setAnimation(Animation::DROP);
        $marker->setOption('flat', true);

        $infoWindow = new InfoWindow();

        $infoWindow->setPrefixJavascriptVariable('info_window_');
        $infoWindow->setContent($this->renderView('HolloTrackerBundle:Dashboard:infobox.html.twig', array(
            'position' => $entity
        )));
        $infoWindow->setAutoClose(true);

        $marker->setInfoWindow($infoWindow);

        $map->addMarker($marker);
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
