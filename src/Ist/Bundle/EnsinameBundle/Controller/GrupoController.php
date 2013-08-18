<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Grupo;
use Ist\Bundle\EnsinameBundle\Form\GrupoType;

/**
 * Grupo controller.
 *
 * @Route("/grupo")
 */
class GrupoController extends Controller
{

    /**
     * Lists all Grupo entities.
     *
     * @Route("/", name="grupo")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not implemented');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('IstEnsinameBundle:Grupo')->findAll();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();
        $professores = $em->getRepository('IstEnsinameBundle:Professor')->findAll();
        if (!empty($entities))
            foreach ($entities as &$entity) {
                if (!empty($linguas))
                    foreach ($linguas as $lingua)
                        if ($entity->getLingua() == $lingua->getId())
                            $entity->setLingua($lingua->getTitulo());
                if (!empty($professores))
                    foreach ($professores as $professor)
                        if ($entity->getProfessor() == $professor->getId())
                            $entity->setProfessor($professor->getNome());
            }
        return array(
            'entities' => $entities,
            'linguas' => $linguas,
            'professores' => $professores,
        );
    }
    /**
     * Creates a new Grupo entity.
     *
     * @Route("/", name="grupo_create")
     * @Method("POST")
     * @Template("IstEnsinameBundle:Grupo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $entity  = new Grupo();
        $form = $this->createForm(new GrupoType($alunos), $entity);
        $form->bind($request);
        if ($form->isValid()) {
            $post = $request->request->get($form->getName());
            $entity->setLingua($post['lingua']);
            $entity->setProfessor($post['professor']);
            $entity->setAlunos(isset($post['alunos']) ? implode(',', $post['alunos']) : null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'grupo criado com sucesso!');
            return $this->redirect($this->generateUrl('grupo'));
        }
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Grupo entity.
     *
     * @Route("/new", name="grupo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = new Grupo();
        $form   = $this->createForm(new GrupoType(), $entity);
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Grupo entity.
     *
     * @Route("/{id}", name="grupo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Grupo')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Grupo entity.');

        $lingua = $em->getRepository('IstEnsinameBundle:Lingua')->find($entity->getLingua());
        $entity->setLingua($lingua->getTitulo());

        $professor = $em->getRepository('IstEnsinameBundle:Professor')->find($entity->getProfessor());
        $entity->setProfessor($professor->getNome());

        $alunos = explode(',', $entity->getAlunos());

        foreach ($alunos as &$aluno)
        {
            $_aluno = $em->getRepository('IstEnsinameBundle:Aluno')->find($aluno);
            $aluno = $_aluno->getNome();
        }

        $entity->setAlunos(implode(',', $alunos));

        return array('entity' => $entity);
    }

    /**
     * Displays a form to edit an existing Grupo entity.
     *
     * @Route("/{id}/edit", name="grupo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Grupo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $editForm = $this->createForm(new GrupoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Grupo entity.
     *
     * @Route("/{id}", name="grupo_update")
     * @Method("PUT")
     * @Template("IstEnsinameBundle:Grupo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Grupo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new GrupoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grupo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Grupo entity.
     *
     * @Route("/{id}/delete", name="grupo_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Grupo')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Grupo entity.');

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'grupo excluido com sucesso!');
        return $this->redirect($this->generateUrl('grupo'));
    }
}
