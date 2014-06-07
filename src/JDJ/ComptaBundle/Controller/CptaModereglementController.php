<?php

namespace JDJ\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JDJ\ComptaBundle\Entity\CptaModereglement;
use JDJ\ComptaBundle\Form\CptaModereglementType;

/**
 * CptaModereglement controller.
 *
 */
class CptaModereglementController extends Controller
{

    /**
     * Lists all CptaModereglement entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JDJComptaBundle:CptaModereglement')->findAll();

        return $this->render('JDJComptaBundle:CptaModereglement:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new CptaModereglement entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new CptaModereglement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cptamodereglement_show', array('id' => $entity->getId())));
        }

        return $this->render('JDJComptaBundle:CptaModereglement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a CptaModereglement entity.
    *
    * @param CptaModereglement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(CptaModereglement $entity)
    {
        $form = $this->createForm(new CptaModereglementType(), $entity, array(
            'action' => $this->generateUrl('cptamodereglement_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new CptaModereglement entity.
     *
     */
    public function newAction()
    {
        $entity = new CptaModereglement();
        $form   = $this->createCreateForm($entity);

        return $this->render('JDJComptaBundle:CptaModereglement:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a CptaModereglement entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaModereglement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaModereglement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaModereglement:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing CptaModereglement entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaModereglement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaModereglement entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('JDJComptaBundle:CptaModereglement:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a CptaModereglement entity.
    *
    * @param CptaModereglement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(CptaModereglement $entity)
    {
        $form = $this->createForm(new CptaModereglementType(), $entity, array(
            'action' => $this->generateUrl('cptamodereglement_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing CptaModereglement entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JDJComptaBundle:CptaModereglement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CptaModereglement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cptamodereglement_edit', array('id' => $id)));
        }

        return $this->render('JDJComptaBundle:CptaModereglement:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a CptaModereglement entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JDJComptaBundle:CptaModereglement')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find CptaModereglement entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cptamodereglement'));
    }

    /**
     * Creates a form to delete a CptaModereglement entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cptamodereglement_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}