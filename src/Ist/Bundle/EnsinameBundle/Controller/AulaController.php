<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Aula;
use Ist\Bundle\EnsinameBundle\Form\AulaType;

/**
 * Aula controller.
 *
 * @Route("/aula")
 */
class AulaController extends Controller
{

    /**
     * Lists all Aula entities.
     *
     * @Route("/", name="aula")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IstEnsinameBundle:Aula')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Aula entity.
     *
     * @Route("/", name="aula_create")
     * @Method("POST")
     * @Template("IstEnsinameBundle:Aula:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Aula();
        $form = $this->createForm(new AulaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $post = $request->request->get($form->getName());
//die(var_dump($post));
            $entity->setProfessor($post['professor']);
            $entity->setGrupo($post['grupo']);
            $entity->setPresencas(isset($post['presencas']) ? implode(',', $post['presencas']) : null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'aula cadastrada com sucesso!');
            return $this->redirect($this->generateUrl('aula_new'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Aula entity.
     *
     * @Route("/new", name="aula_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Aula();
        $form   = $this->createForm(new AulaType(), $entity);
        $em = $this->getDoctrine()->getManager();
        $professores = $em->getRepository('IstEnsinameBundle:Professor')->findAll();
        
        if (empty($professores))
            $professores = array();

        $grupos = $em->getRepository('IstEnsinameBundle:Grupo')->findAll();
        
        if (empty($grupos))
            $grupos = array();

        $data = array();

        foreach ($professores as $professor)
            foreach ($grupos as $grupo)
                if ($professor->getId() == $grupo->getProfessor())
                    $data[$professor->getId()][$grupo->getId()] = explode(',', $grupo->getAlunos());

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'data'   => json_encode($data),
        );
    }

    /**
     * Finds and displays a Aula entity.
     *
     * @Route("/{id}", name="aula_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Aula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aula entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Aula entity.
     *
     * @Route("/{id}/edit", name="aula_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Aula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aula entity.');
        }

        $editForm = $this->createForm(new AulaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Aula entity.
     *
     * @Route("/{id}", name="aula_update")
     * @Method("PUT")
     * @Template("IstEnsinameBundle:Aula:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Aula')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aula entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AulaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aula_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Aula entity.
     *
     * @Route("/{id}", name="aula_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IstEnsinameBundle:Aula')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Aula entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('aula'));
    }

    /**
     * Creates a form to delete a Aula entity by id.
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
