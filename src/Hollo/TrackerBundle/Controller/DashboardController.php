<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\Polyline;
use Hollo\TrackerBundle\Entity\Fraction;
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
    public function indexAction()
    {
        $map = $this->get('ivory_google_map.map');
        $map->setCenter(57.0445, 9.93, true);
        $map->setMapOption('zoom', 15);
        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '600px');

        $polyline = new Polyline();
        $polyline->addCoordinate(57.038030, 9.946145, true);
        $polyline->addCoordinate(57.038199, 9.948462, true);
        $polyline->addCoordinate(57.038980, 9.948215, true);
        $polyline->addCoordinate(57.039230, 9.950801, true);
        $polyline->addCoordinate(57.040392, 9.950119, true);
        $polyline->addCoordinate(57.040342, 9.949422, true);
        $polyline->addCoordinate(57.041355, 9.949476, true);
        $polyline->addCoordinate(57.042689, 9.949154, true);
        $polyline->addCoordinate(57.042686, 9.950334, true);
        $polyline->addCoordinate(57.046208, 9.945023, true);
        $polyline->addCoordinate(57.046742, 9.944165, true);
        $polyline->addCoordinate(57.046985, 9.931506, true);
        $polyline->addCoordinate(57.048505, 9.926313, true);
        $polyline->addCoordinate(57.050988, 9.921378, true);
        $polyline->addCoordinate(57.050530, 9.920777, true);
        $polyline->addCoordinate(57.051286, 9.916786, true);
        $polyline->addCoordinate(57.044385, 9.911947, true);

        $map->addPolyline($polyline);

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('HolloTrackerBundle:User')->findAll();

        foreach ($entities as $entity) {
            $position = $entity->getPosition();

            $marker = new Marker();

            // Configure your marker options
            $marker->setPrefixJavascriptVariable('marker_');
            $marker->setPosition($position->getLatitude(), $position->getLongitude(), true);
            $marker->setAnimation(Animation::DROP);

            $marker->setOption('flat', true);

            $map->addMarker($marker);
        }

        return array(
            'map' => $map
        );
    }
}
