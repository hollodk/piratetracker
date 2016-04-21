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
        $r = $em->getRepository('HolloTrackerBundle:User')->findOneByMapFollow(true);
        if ($r && $r->getPosition()) {
            $center = array(
                'lat' => $r->getPosition()->getLatitude(),
                'lon' => $r->getPosition()->getLongitude()
            );
        }

        $fractions = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();

        return array(
            'center' => $center,
            'zoom' => 14,
            'fractions' => $fractions
        );
    }

    /**
     * @Route("/{id}/track")
     * @Template("HolloTrackerBundle:Dashboard:index.html.twig")
     */
    public function trackAction(Request $request, User $user)
    {
        $this->baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $em = $this->getDoctrine()->getManager();

        $center = null;
        if ($user->getPosition()) {
            $center = array(
                'lat' => $user->getPosition()->getLatitude(),
                'lon' => $user->getPosition()->getLongitude()
            );
        }

        $fractions = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();

        return array(
            'center' => $center,
            'zoom' => 14,
            'fractions' => $fractions,
            'currentUser' => $user
        );
    }
}
