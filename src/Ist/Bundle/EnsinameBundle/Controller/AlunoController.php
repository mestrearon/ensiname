<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Aluno;
use Ist\Bundle\EnsinameBundle\Form\AlunoType;

/**
 * Aluno controller.
 *
 * @Route("/aluno")
 */
class AlunoController extends Controller
{

    /**
     * Lists all Aluno entities.
     *
     * @Route("/", name="aluno")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$entities = $em->getRepository('IstEnsinameBundle:Aluno')->findAll();
        $entities = $em->getRepository('IstEnsinameBundle:Aluno')->createQueryBuilder('a')->orderBy('a.nome', 'ASC')->getQuery()->getResult();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();
        return array(
            'entities' => $entities,
            'linguas' => $linguas);
    }
    /**
     * Creates a new Aluno entity.
     *
     * @Route("/", name="aluno_create")
     * @Method("POST")
     * @Template("IstEnsinameBundle:Aluno:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Aluno();
        $form = $this->createForm(new AlunoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            //$nascimento = $request->request->get('nascimento', date('Y/m/d'));
            //$entity->setNascimento($nascimento);

            $em = $this->getDoctrine()->getManager();
            foreach ($request->request->get('linguas', array()) as $lingua) {
                $_lingua = $em->getRepository('IstEnsinameBundle:Lingua')->find($lingua);
                $linguas[] = $_lingua->getTitulo();
            }
            $linguas = isset($linguas) ? implode(", ", $linguas) : '-';
            $entity->setLinguas($linguas);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'aluno cadastrado com sucesso!');
            return $this->redirect($this->generateUrl('aluno'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'falha ao cadastrar aluno! (dados inválidos)');
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Aluno entity.
     *
     * @Route("/new", name="aluno_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Aluno();
        $form   = $this->createForm(new AlunoType(), $entity);

        $em = $this->getDoctrine()->getManager();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'linguas' => $linguas,
        );
    }

    /**
     * Finds and displays a Aluno entity.
     *
     * @Route("/{id}", name="aluno_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Aluno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aluno entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Aluno entity.
     *
     * @Route("/{id}/edit", name="aluno_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Aluno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aluno entity.');
        }

        $editForm = $this->createForm(new AlunoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Aluno entity.
     *
     * @Route("/{id}", name="aluno_update")
     * @Method("PUT")
     * @Template("IstEnsinameBundle:Aluno:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IstEnsinameBundle:Aluno')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Aluno entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AlunoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('aluno_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Aluno entity.
     *
     * @Route("/{id}", name="aluno_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IstEnsinameBundle:Aluno')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Aluno entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('aluno'));
    }

    /**
     * Creates a form to delete a Aluno entity by id.
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
