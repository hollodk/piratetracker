<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\User;

/**
 * @Route("/user/profile/password")
 */
class UserPasswordController extends Controller
{
    /**
     * @Route("", name="user_password")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('user_password'))
            ->setMethod('POST')
            ->add('password', 'password')
            ->add('submit', 'submit', array('label' => 'Update'))
            ->getForm()
            ;

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice', 'Password has been changed');

            return $this->redirect($this->generateUrl('hollo_tracker_dashboard_index'));
        }

        return array(
            'form'   => $form->createView(),
        );
    }
}
