<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Interessado;
use Ist\Bundle\EnsinameBundle\Form\InteressadoType;
use Doctrine\Common\Collections\ArrayCollection;

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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('IstEnsinameBundle:Interessado')->createQueryBuilder('a')->orderBy('a.chamada', 'DESC')->getQuery()->getResult();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        if (!empty($entities)) {
            foreach ($entities as &$entity) {
                foreach (explode(',', $entity->getLinguas()) as $lingua_ent)
                    if (!empty($lingua_ent) &&!empty($linguas))
                        foreach ($linguas as $lingua)
                            if ($lingua_ent == $lingua->getId())
                                $lingua_new[] = $lingua->getTitulo();
                $lingua_new = isset($lingua_new) ? $lingua_new : array();
                $entity->setLinguas(implode(',', $lingua_new));
                unset($lingua_new);
            }
        }

        return array(
            'entities' => $entities,
            'linguas' => $linguas,
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

            return $this->redirect($this->generateUrl('interessado'));
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

        // $form->add('submit', 'submit', array('label' => 'Create'));

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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Interessado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessado entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
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
        $em = $this->getDoctrine()->getManager();

        $linguas = $entity->hasLinguas()
            ? $em->getRepository('IstEnsinameBundle:Lingua')->createQueryBuilder('a')->where('a.id in ('. $entity->getLinguas() .')')->getQuery()->getResult()
            : array();

        $collection = new ArrayCollection($linguas);

        $entity->setLinguas($collection);

        $form = $this->createForm(new InteressadoType(), $entity, array(
            'action' => $this->generateUrl('interessado_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        // $form->add('submit', 'submit', array('label' => 'Update'));

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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Interessado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interessado entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $post = $request->request->get($editForm->getName());
            $entity->setLinguas(isset($post['linguas']) ? implode(',', $post['linguas']) : null);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'interessado editado com sucesso!');
            return $this->redirect($this->generateUrl('interessado'));
        }

        $this->get('session')->getFlashBag()->add('error', 'falha ao editar interessado!');

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Interessado entity.
     *
     * @Route("/{id}/delete", name="interessado_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Interessado')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Interessado entity.');

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'interessado excluído com sucesso!');
        return $this->redirect($this->generateUrl('interessado'));
    }
}
