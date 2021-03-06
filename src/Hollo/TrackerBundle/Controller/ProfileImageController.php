<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\User;
use Hollo\TrackerBundle\Entity\Image;
use Hollo\TrackerBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/user/profile/image")
 */
class ProfileImageController extends Controller
{
    /**
     * Edits an existing User entity.
     *
     * @Route("", name="user_profile_image")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $entity = $this->getUser();
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('user_profile_image'))
            ->setMethod('PUT')
            ->add('file', 'file')
            ->add('submit', 'submit', array('label' => 'Update'))
            ->getForm()
            ;

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $image = new Image();
            $image->setUser($this->getUser());
            $image->setImage(base64_encode(file_get_contents($data['file']->getPathName())));

            $entity->setImage($image);

            $em->persist($image);
            $em->flush();

            return $this->redirect($this->generateUrl('hollo_tracker_dashboard_index'));
        }

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }
}
