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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('IstEnsinameBundle:Aula')->findAll();
        $grupos = $em->getRepository('IstEnsinameBundle:Grupo')->findAll();
        if (!empty($entities))
            foreach ($entities as &$entity)
                if (!empty($grupos))
                    foreach ($grupos as $grupo)
                        if ($grupo->getId() == $entity->getGrupo())
                            $entity->setGrupo($grupo->getTitulo());
        return array(
            'entities' => $entities,
            'grupos' => $grupos,
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

            if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
                $professor = $post['professor'];

            if ($this->get('security.context')->isGranted('ROLE_PROF'))
                $professor = $this->getUser()->getId();

            $entity->setProfessor($professor);
            $entity->setGrupo(isset($post['grupo']) ? $post['grupo'] : NULL);
            $entity->setPresencas(isset($post['presencas']) ? implode(',', $post['presencas']) : NULL);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'aula cadastrada com sucesso!');
            return $this->redirect($this->generateUrl($this->get('security.context')->isGranted('ROLE_ADMIN') ? 'aula' : 'aula_new'));
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

        if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
            $grupos = $em->getRepository('IstEnsinameBundle:Grupo')->findAll();

        if ($this->get('security.context')->isGranted('ROLE_PROF'))
            $grupos = $em->getRepository('IstEnsinameBundle:Grupo')->findBy(array('professor' => $this->getUser()->getId()));
        
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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Aula')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Aula entity.');

        $professor = $em->getRepository('IstEnsinameBundle:Professor')->find($entity->getProfessor());
        $entity->setProfessor($professor->getNome());
        if ($entity->getGrupo())
            $grupo = $em->getRepository('IstEnsinameBundle:Grupo')->find($entity->getGrupo());
        
        if (isset($grupo))
            $entity->setGrupo(array(
                'id' => $grupo->getId(),
                'titulo' => $grupo->getTitulo(),
            ));

        if ($entity->getPresencas())
        {
            $presencas = explode(',', $entity->getPresencas());

            foreach ($presencas as &$presenca)
            {
                $_presenca = $em->getRepository('IstEnsinameBundle:Aluno')->find($presenca);
                
                if ($_presenca)
                    $presenca = $_presenca->getNome();
            }

            $entity->setPresencas($presencas);
        }

        return array('entity' => $entity);
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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

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
     * @Route("/{id}/delete", name="aula_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Aula')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Aula entity.');

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'aula excluido com sucesso!');
        return $this->redirect($this->generateUrl('aula'));
    }
}
