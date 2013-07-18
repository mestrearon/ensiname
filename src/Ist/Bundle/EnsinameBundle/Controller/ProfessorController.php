<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Professor;
use Ist\Bundle\EnsinameBundle\Form\ProfessorType;

/**
 * Professor controller.
 *
 * @Route("/professor")
 */
class ProfessorController extends Controller
{

    /**
     * Lists all Professor entities.
     *
     * @Route("/", name="professor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('IstEnsinameBundle:Professor')->findAll();
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Professor entity.
     *
     * @Route("/", name="professor_create")
     * @Method("POST")
     * @Template("IstEnsinameBundle:Professor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Professor();
        $form = $this->createForm(new ProfessorType(), $entity);
        $form->bind($request);
        $post = $request->request->get($form->getName());
        $entity->setLinguas(isset($post['linguas']) ? implode(',', $post['linguas']) : null);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Professor cadastrado com sucesso! ');
            return $this->redirect($this->generateUrl('professor_new'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Falha ao cadastrar professor!');
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Professor entity.
     *
     * @Route("/new", name="professor_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Professor();
        $form   = $this->createForm(new ProfessorType(), $entity);
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Professor entity.
     *
     * @Route("/{id}", name="professor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Professor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Professor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Professor entity.
     *
     * @Route("/{id}/edit", name="professor_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Professor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Professor entity.');
        }

        $editForm = $this->createForm(new ProfessorType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Professor entity.
     *
     * @Route("/{id}", name="professor_update")
     * @Method("PUT")
     * @Template("IstEnsinameBundle:Professor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Professor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Professor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProfessorType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('professor_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Professor entity.
     *
     * @Route("/{id}", name="professor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IstEnsinameBundle:Professor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Professor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('professor'));
    }

    /**
     * Creates a form to delete a Professor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
