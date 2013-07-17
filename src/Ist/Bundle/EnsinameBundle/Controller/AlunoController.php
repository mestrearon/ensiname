<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Aluno;
use Ist\Bundle\EnsinameBundle\Entity\Lingua;
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
        $entities = $em->getRepository('IstEnsinameBundle:Aluno')->createQueryBuilder('a')->orderBy('a.nome', 'ASC')->getQuery()->getResult();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        if (!empty($entities))
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
        $em = $this->getDoctrine()->getManager();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        $entity  = new Aluno();

        $form = $this->createForm(new AlunoType($linguas), $entity);
        $form->bind($request);

        $isValid = true;

        $post = $request->request->get($form->getName());
        if (isset($post['linguas']))
            foreach ($post['linguas'] as &$lingua)
                $lingua = $linguas[(int) $lingua]->getId();
        $entity->setLinguas(implode(',', isset($post['linguas']) ? $post['linguas'] : array()));

        if (isset($data['nascimento'])) {
            if (preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $data['nascimento'], $matches)) {
                $entity->setNascimento($matches[1] .'/'. $matches[2] .'/'. $matches[3]);
            } else {
                    #throw new \Exception('invalid data value 2');
                $isValid = false;
            }
        }

        if ($form->isValid() && $isValid) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Студент успешно зарегистрирован!');
            return $this->redirect($this->generateUrl('aluno'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Не удалось зарегистрировать студента!');
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
        $em = $this->getDoctrine()->getManager();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        $entity = new Aluno();

        $form = $this->createForm(new AlunoType($linguas), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Aluno entity.');

        $linguas = explode(',', $entity->getLinguas());
        foreach ($linguas as &$lingua)
            $lingua = (int) $lingua;
        $entity->setLinguas($linguas);

        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        $form = $this->createForm(new AlunoType($linguas), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Aluno entity.');

        $linguas = explode(',', $entity->getLinguas());
        foreach ($linguas as &$lingua)
            $lingua = (int) $lingua;
        $entity->setLinguas($linguas);

        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();

        $form = $this->createForm(new AlunoType($linguas), $entity);
        $form->bind($request);

        if ($form->isValid())
        {
            $post = $request->request->get($form->getName());
            if (isset($post['linguas']))
                foreach ($post['linguas'] as &$lingua)
                    $lingua = $linguas[(int) $lingua]->getId();
            $entity->setLinguas(implode(',', isset($post['linguas']) ? $post['linguas'] : array()));
            
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Студент успешно edited!');
            return $this->redirect($this->generateUrl('aluno'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'invalid data!');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $form->createView(),
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
