<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Grupo;
use Ist\Bundle\EnsinameBundle\Form\GrupoType;
use Doctrine\Common\Collections\ArrayCollection;

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
            $this->get('session')->getFlashBag()->add('success', 'Новая группа успешно добавлена!');
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

        foreach ($alunos as $aluno)
        {
            $_aluno = $em->getRepository('IstEnsinameBundle:Aluno')->find($aluno);

            if ($_aluno)
                $_alunos[] = $_aluno->getNome();
        }

        $entity->setAlunos(implode(',', (array) $_alunos));

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
            return $this->redirect($this->generateUrl('grupo'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Grupo')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('info', 'invalid group');
            return $this->redirect($this->generateUrl('grupo'));
        }

        $lingua = $em->getRepository('IstEnsinameBundle:Lingua')->find($entity->getLingua());

        if ($lingua) {
            $entity->setLingua($lingua);
        }

        $professor = $em->getRepository('IstEnsinameBundle:Professor')->find($entity->getProfessor());

        if ($professor) {
            $entity->setProfessor($professor);
        }

        $this->getAlunos($entity);

        $form = $this->createForm(new GrupoType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Grupo')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add('info', 'invalid parameter');
            return $this->redirect($this->generateUrl('grupo'));
        }

        $this->getAlunos($entity);
        $form = $this->createForm(new GrupoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $post = $request->request->get($form->getName());
            $entity->setLingua($post['lingua']);
            $entity->setProfessor($post['professor']);
            $entity->setAlunos(isset($post['alunos']) ? implode(',', $post['alunos']) : null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Информация о группе успешно обновлена!');
            return $this->redirect($this->generateUrl('grupo'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Ошибка при обновлении группы!');
        }

        return array(
            'entity' => $entity,
            'edit_form' => $form->createView(),
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

        $this->get('session')->getFlashBag()->add('success', 'Группа успешно удалена!');
        return $this->redirect($this->generateUrl('grupo'));
    }

    private function getAlunos(&$entity)
    {
        $presencas = $entity->hasAlunos()
            ? $this->getDoctrine()->getManager()->getRepository('IstEnsinameBundle:Aluno')->createQueryBuilder('a')->where('a.id in ('. $entity->getAlunos() .')')->getQuery()->getResult()
            : array();
        $collection = new ArrayCollection($presencas);
        $entity->setAlunos($collection);
    }
}
