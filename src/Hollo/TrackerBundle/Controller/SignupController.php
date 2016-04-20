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
class SignupController extends Controller
{
    /**
     * @Route("/signup")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('hollo_tracker_signup_index'))
            ->add('username', 'text')
            ->add('password', 'password')
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('submit', 'submit')
            ->getForm()
            ;

        $form->handleRequest($request);
        if ($form->isValid()) {

            $data = $form->getData();

            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword($data['password']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Your pirate has been created');

            return $this->redirect($this->generateUrl('hollo_tracker_dashboard_index'));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/signup/reset")
     * @Template()
     */
    public function resetAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('hollo_tracker_signup_reset'))
            ->add('username', 'text')
            ->add('email', 'email')
            ->add('submit', 'submit')
            ->getForm()
            ;

        $form->handleRequest($request);
        if ($form->isValid()) {

            $data = $form->getData();

            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword($data['password']);
            $user->setName($data['name']);
            $user->setEmail($data['email']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Your pirate has been created');

            return $this->redirect($this->generateUrl('hollo_tracker_dashboard_index'));
        }

        return array(
            'form' => $form->createView()
        );
    }

}
