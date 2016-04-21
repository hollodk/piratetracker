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
use Hollo\TrackerBundle\Entity\Reset;
use Hollo\TrackerBundle\Form\FractionType;
use Ramsey\Uuid\Uuid;

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
            ->add('username', 'text', array(
                'required' => false
            ))
            ->add('email', 'email', array(
                'required' => false
            ))
            ->add('submit', 'submit')
            ->getForm()
            ;

        $form->handleRequest($request);
        if ($form->isValid()) {

            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('HolloTrackerBundle:User')->findOneByUsername($data['username']);
            if (!$user) {
                $user = $em->getRepository('HolloTrackerBundle:User')->findOneByEmail($data['email']);
            }

            if ($user) {
                $expire = new \DateTime();
                $expire->modify('+1 day');

                $uuid4 = Uuid::uuid4();

                $reset = new Reset();
                $reset->setExpireAt($expire);
                $reset->setHash($uuid4->toString());
                $reset->setUser($user);

                $em->persist($reset);
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Reset password, pirattogt.dk')
                    ->setFrom('noreply@pirattogt.dk')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'HolloTrackerBundle:Emails:reset.html.twig',
                            array('reset' => $reset)
                        ),
                        'text/html'
                    )
                    ;

                $this->get('mailer')->send($message);

                $request->getSession()->getFlashBag()->add('notice', 'We have sent you an reset password mail');

                return $this->redirect($this->generateUrl('hollo_tracker_dashboard_index'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/signup/hash")
     * @Template()
     */
    public function hashAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $reset = $em->getRepository('HolloTrackerBundle:Reset')->findOneByHash($request->get('hash'));
        if (!$reset) {
            $this->addFlash('error', 'Could not find the hash in the database');

            return $this->redirect($this->generateUrl('hollo_tracker_dashboard_index'));
        }

        if ($reset->getExpireAt() < new \DateTime()) {
            $this->addFlash('error', 'Hash has expired');

            return $this->redirect($this->generateUrl('hollo_tracker_dashboard_index'));

        }

        $reset->setExpireAt(new \DateTime());

        $password = rand(10000, 99999);
        $reset->getUser()->setPassword($password);

        $em->flush();

        return array(
            'user' => $reset->getUser(),
            'password' => $password
        );
    }

}
