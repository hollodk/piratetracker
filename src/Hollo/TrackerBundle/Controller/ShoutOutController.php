<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\ShoutOut;
use Hollo\TrackerBundle\Form\ShoutOutType;

/**
 * ShoutOut controller.
 *
 * @Route("/admin/shoutout")
 */
class ShoutOutController extends Controller
{

    /**
     * Lists all ShoutOut entities.
     *
     * @Route("/", name="admin_shoutout")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HolloTrackerBundle:ShoutOut')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new ShoutOut entity.
     *
     * @Route("/", name="admin_shoutout_create")
     * @Method("POST")
     * @Template("HolloTrackerBundle:ShoutOut:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ShoutOut();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $gcm = $this->get('hollo_tracker.gcm');
            $gcm->sendMessage('PiratTitle', $entity->getContent());

            return $this->redirect($this->generateUrl('admin_shoutout_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a ShoutOut entity.
     *
     * @param ShoutOut $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ShoutOut $entity)
    {
        $form = $this->createForm(new ShoutOutType(), $entity, array(
            'action' => $this->generateUrl('admin_shoutout_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ShoutOut entity.
     *
     * @Route("/new", name="admin_shoutout_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ShoutOut();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a ShoutOut entity.
     *
     * @Route("/{id}", name="admin_shoutout_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HolloTrackerBundle:ShoutOut')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShoutOut entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ShoutOut entity.
     *
     * @Route("/{id}/edit", name="admin_shoutout_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HolloTrackerBundle:ShoutOut')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShoutOut entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a ShoutOut entity.
    *
    * @param ShoutOut $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ShoutOut $entity)
    {
        $form = $this->createForm(new ShoutOutType(), $entity, array(
            'action' => $this->generateUrl('admin_shoutout_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ShoutOut entity.
     *
     * @Route("/{id}", name="admin_shoutout_update")
     * @Method("PUT")
     * @Template("HolloTrackerBundle:ShoutOut:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HolloTrackerBundle:ShoutOut')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShoutOut entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_shoutout_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a ShoutOut entity.
     *
     * @Route("/{id}", name="admin_shoutout_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HolloTrackerBundle:ShoutOut')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ShoutOut entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_shoutout'));
    }

    /**
     * Creates a form to delete a ShoutOut entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_shoutout_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
