<?php

namespace Hollo\TrackerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Hollo\TrackerBundle\Entity\Fraction;
use Hollo\TrackerBundle\Form\FractionType;

/**
 * Fraction controller.
 *
 * @Route("/admin/fraction")
 */
class FractionController extends Controller
{

    /**
     * Lists all Fraction entities.
     *
     * @Route("/", name="admin_fraction")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('HolloTrackerBundle:Fraction')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Fraction entity.
     *
     * @Route("/", name="admin_fraction_create")
     * @Method("POST")
     * @Template("HolloTrackerBundle:Fraction:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Fraction();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_fraction_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Fraction entity.
     *
     * @param Fraction $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Fraction $entity)
    {
        $form = $this->createForm(new FractionType(), $entity, array(
            'action' => $this->generateUrl('admin_fraction_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Fraction entity.
     *
     * @Route("/new", name="admin_fraction_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Fraction();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Fraction entity.
     *
     * @Route("/{id}", name="admin_fraction_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HolloTrackerBundle:Fraction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fraction entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Fraction entity.
     *
     * @Route("/{id}/edit", name="admin_fraction_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HolloTrackerBundle:Fraction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fraction entity.');
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
    * Creates a form to edit a Fraction entity.
    *
    * @param Fraction $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Fraction $entity)
    {
        $form = $this->createForm(new FractionType(), $entity, array(
            'action' => $this->generateUrl('admin_fraction_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Fraction entity.
     *
     * @Route("/{id}", name="admin_fraction_update")
     * @Method("PUT")
     * @Template("HolloTrackerBundle:Fraction:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('HolloTrackerBundle:Fraction')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fraction entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_fraction_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Fraction entity.
     *
     * @Route("/{id}", name="admin_fraction_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('HolloTrackerBundle:Fraction')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Fraction entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_fraction'));
    }

    /**
     * Creates a form to delete a Fraction entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_fraction_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
