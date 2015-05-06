<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\User;
use Hollo\TrackerBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/admin/user/profile/image")
 */
class ProfileImageController extends Controller
{
    /**
     * Edits an existing User entity.
     *
     * @Route("", name="admin_user_profile_image")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $entity = $this->getUser();
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_profile_image'))
            ->setMethod('PUT')
            ->add('file', 'file')
            ->add('submit', 'submit', array('label' => 'Update'))
            ->getForm()
            ;

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $entity->setProfileImage(base64_encode(file_get_contents($data['file']->getPathName())));

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user'));
        }

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }
}
