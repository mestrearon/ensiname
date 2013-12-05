<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Lingua;
use Ist\Bundle\EnsinameBundle\Form\LinguaType;

/**
 * Lingua controller.
 *
 * @Route("/lingua")
 */
class LinguaController extends Controller
{

    /**
     * Lists all Lingua entities.
     *
     * @Route("/", name="lingua")
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
        $entities = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Lingua entity.
     *
     * @Route("/", name="lingua_create")
     * @Method("POST")
     * @Template("IstEnsinameBundle:Lingua:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $entity  = new Lingua();
        $form = $this->createForm(new LinguaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Язык успешно зарегистрирован');
                return $this->redirect($this->generateUrl('lingua'));
            }
            catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Язык уже существует');
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Не удалось зарегистрировать язык');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Lingua entity.
     *
     * @Route("/new", name="lingua_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $entity = new Lingua();
        $form   = $this->createForm(new LinguaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Lingua entity.
     *
     * @Route("/{id}", name="lingua_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Lingua')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lingua entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Lingua entity.
     *
     * @Route("/{id}/edit", name="lingua_edit")
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

        $entity = $em->getRepository('IstEnsinameBundle:Lingua')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lingua entity.');
        }

        $editForm = $this->createForm(new LinguaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Lingua entity.
     *
     * @Route("/{id}", name="lingua_update")
     * @Method("PUT")
     * @Template("IstEnsinameBundle:Lingua:edit.html.twig")
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

        $entity = $em->getRepository('IstEnsinameBundle:Lingua')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lingua entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LinguaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('lingua_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Lingua entity.
     *
     * @Route("/{id}", name="lingua_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IstEnsinameBundle:Lingua')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lingua entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lingua'));
    }

    /**
     * Creates a form to delete a Lingua entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
