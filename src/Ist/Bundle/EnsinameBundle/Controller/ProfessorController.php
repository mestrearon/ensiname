<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Professor;
use Ist\Bundle\EnsinameBundle\Form\ProfessorType;
use Doctrine\Common\Collections\ArrayCollection;

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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('IstEnsinameBundle:Professor')->findAll();
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();
        $grupos = $em->getRepository('IstEnsinameBundle:Grupo')->findAll();
        $professores = array();
        if (!empty($entities))
            foreach ($entities as &$entity) {
                foreach (explode(',', $entity->getLinguas()) as $lingua_ent)
                    if (!empty($lingua_ent) && !empty($linguas))
                        foreach ($linguas as $lingua)
                            if ($lingua_ent == $lingua->getId())
                                $lingua_new[] = $lingua->getTitulo();
                $lingua_new = isset($lingua_new) ? $lingua_new : array();
                $entity->setLinguas(implode(',', $lingua_new));
                unset($lingua_new);
                if (!empty($grupos))
                    foreach ($grupos as $grupo)
                        if ($grupo->getProfessor() == $entity->getId())
                            $grupo_new[] = $grupo->getTitulo();
                $grupo_new = isset($grupo_new) ? $grupo_new : array();
                $professores[$entity->getId()] = implode(',', $grupo_new);
                unset($grupo_new);
            }
        return array(
            'entities' => $entities,
            'linguas' => $linguas,
            'grupos' => $grupos,
            'professores' => $professores,
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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $entity  = new Professor();
        $form = $this->createForm(new ProfessorType(), $entity);
        $form->bind($request);
        $post = $request->request->get($form->getName());
        $entity->setLinguas(isset($post['linguas']) ? implode(',', $post['linguas']) : null);
        if ($form->isValid()) {


            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $post = $request->request->get($form->getName());
            $password = $encoder->encodePassword($post['password'], $entity->getSalt());
            $entity->setPassword($password);


            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Новый преподаватель успешно добавлен! ');
            return $this->redirect($this->generateUrl('professor'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Ошибка при добавлении преподавателя!');
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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $this->get('session')->getFlashBag()->add('error', 'not implemented');
        return $this->redirect($this->generateUrl('index'));

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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Professor')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Professor entity.');

        $this->getLinguas($entity);
        $entity->setPassword(null);
        $form = $this->createForm(new ProfessorType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Professor')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Professor entity.');

        $this->getLinguas($entity);
        $form = $this->createForm(new ProfessorType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $post = $request->request->get($form->getName());

            if (!empty($post['password'])) {
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($entity);
                $password = $encoder->encodePassword($post['password'], $entity->getSalt());
                $entity->setPassword($password);
            }

            $entity->setLinguas(isset($post['linguas']) ? implode(',', $post['linguas']) : null);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Информация о преподавателе успешно обновлена! ');
            return $this->redirect($this->generateUrl('professor'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Ошибка при обновлении преподавателя!');
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Deletes a Professor entity.
     *
     * @Route("/{id}/delete", name="professor_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Professor')->find($id);

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Professor entity.');

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Преподаватель успешно исключен!');
        return $this->redirect($this->generateUrl('professor'));
    }

    private function getLinguas(&$entity)
    {
        $linguas = $entity->hasLinguas()
            ? $this->getDoctrine()->getManager()->getRepository('IstEnsinameBundle:Lingua')->createQueryBuilder('a')->where('a.id in ('. $entity->getLinguas() .')')->getQuery()->getResult()
            : array();
        $collection = new ArrayCollection($linguas);
        $entity->setLinguas($collection);
    }
}
