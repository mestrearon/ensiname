<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Interessado;
use Ist\Bundle\EnsinameBundle\Form\InteressadoType;

/**
 * Interessado controller.
 *
 * @Route("/interessado")
 */
class InteressadoController extends Controller
{

    /**
     * Lists all Interessado entities.
     *
     * @Route("/", name="interessado")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $this->get('session')->getFlashBag()->add('error', 'not authorized');
        return $this->redirect($this->generateUrl('index'));

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IstEnsinameBundle:Interessado')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Interessado entity.
     *
     * @Route("/", name="interessado_create")
     * @Method("POST")
     * @Template("IstEnsinameBundle:Interessado:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $entity = new Interessado();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $post = $request->request->get($form->getName());
        $entity->setLinguas(isset($post['linguas']) ? implode(',', $post['linguas']) : null);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Interessado criado com sucesso!');

            return $this->redirect($this->generateUrl('interessado_new'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Falha na criação do Interessado!');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Interessado entity.
    *
    * @param Interessado $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Interessado $entity)
    {
        $form = $this->createForm(new InteressadoType(), $entity, array(
            'action' => $this->generateUrl('interessado_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Interessado entity.
     *
     * @Route("/new", name="interessado_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $entity = new Interessado();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Interessado entity.
     *
     * @Route("/{id}", name="interessado_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $this->get('session')->getFlashBag()->add('error', 'not authorized');
        return $this->redirect($this->generateUrl('index'));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Interessado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Interessado entity.
     *
     * @Route("/{id}/edit", name="interessado_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $this->get('session')->getFlashBag()->add('error', 'not authorized');
        return $this->redirect($this->generateUrl('index'));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Interessado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessado entity.');
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
    * Creates a form to edit a Interessado entity.
    *
    * @param Interessado $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Interessado $entity)
    {
        $form = $this->createForm(new InteressadoType(), $entity, array(
            'action' => $this->generateUrl('interessado_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Interessado entity.
     *
     * @Route("/{id}", name="interessado_update")
     * @Method("PUT")
     * @Template("IstEnsinameBundle:Interessado:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $this->get('session')->getFlashBag()->add('error', 'not authorized');
        return $this->redirect($this->generateUrl('index'));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Interessado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessado entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('interessado_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Interessado entity.
     *
     * @Route("/{id}", name="interessado_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $this->get('session')->getFlashBag()->add('error', 'not authorized');
        return $this->redirect($this->generateUrl('index'));

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IstEnsinameBundle:Interessado')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Interessado entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('interessado'));
    }

    /**
     * Creates a form to delete a Interessado entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('interessado_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
