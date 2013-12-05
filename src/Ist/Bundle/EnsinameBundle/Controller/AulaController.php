<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ist\Bundle\EnsinameBundle\Entity\Aula;
use Ist\Bundle\EnsinameBundle\Form\AulaType;
use Doctrine\Common\Collections\ArrayCollection;

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
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $entities = $em->getRepository('IstEnsinameBundle:Aula')->findBy(array(), array('data' => 'DESC'));
        }
        if ($this->get('security.context')->isGranted('ROLE_PROF')) {
            $entities = $em->getRepository('IstEnsinameBundle:Aula')->findBy(array('professor' => $this->get('security.context')->getToken()->getUser()->getId()), array('data' => 'DESC'));
        }
        if (empty($entities)) {
            $entities = array();
        }
        $grupos = $em->getRepository('IstEnsinameBundle:Grupo')->findAll();
        if (empty($grupos)) {
            $grupos = array();
        }
        $professores = $em->getRepository('IstEnsinameBundle:Professor')->findAll();
        if (empty($professores)) {
            $professores = array();
        }
        $linguas = $em->getRepository('IstEnsinameBundle:Lingua')->findAll();
        if (empty($linguas)) {
            $linguas = array();
        }
        foreach ($entities as &$entity) {
            $g = null;
            $l = null;
            foreach ($grupos as $grupo) {
                if ($grupo->getId() == $entity->getGrupo()) {
                    $g = $grupo->getTitulo();
                    foreach ($linguas as $lingua) {
                        if ($lingua->getId() == $grupo->getLingua()) {
                            $l = $lingua->getTitulo();
                            break;
                        }
                    }
                    break;
                }
            }
            $p = null;
            foreach ($professores as $professor) {
                if ($professor->getId() == $entity->getProfessor()) {
                    $p = $professor->getNome();
                    break;
                }
            }
            $entity = array(
                'id' => $entity->getId(),
                'data' => $entity->getData(),
                'grupo' => $g,
                'professor' => $p,
                'lingua' => $l,
                'dada' => $entity->getDada(),
                'observacao' => $entity->getObservacao(),
            );
        }
        return array(
            'entities' => array_filter($entities),
            'grupos' => $grupos,
            'professores' => $professores,
            'linguas' => $linguas,
            'ehAdmin' => $this->get('security.context')->isGranted('ROLE_ADMIN'),
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

            try {
                list($dia, $mes, $ano) = explode('/', $post['data']);
                $entity->setData(new \DateTime($ano .'-'.$mes .'-'.$dia));
            }
            catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'data invalida!');
                return $this->newAction($entity);
            }

            $entity->setGrupo(isset($post['grupo']) ? $post['grupo'] : NULL);
            $entity->setPresencas(isset($post['presencas']) ? implode(',', $post['presencas']) : NULL);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Урок успешно зарегистрирован!');
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
    public function newAction($entity = null)
    {
        $entity = empty($entity) ? new Aula() : $entity;
        $form   = $this->createForm(new AulaType(), $entity);
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'data' => json_encode($this->getData()),
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

        if (!$entity)
            throw $this->createNotFoundException('Unable to find Aula entity.');

        if ($this->get('security.context')->isGranted('ROLE_PROF') && ($entity->getProfessor() != $this->get('security.context')->getToken()->getUser()->getId())) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('index'));
        }

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
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('IstEnsinameBundle:Aula')->find($id);

        if (!$entity || $this->get('security.context')->isGranted('ROLE_PROF') && ($entity->getProfessor() != $this->getUser()->getId())) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('aula'));
        }

        $this->getPresencas($entity);
        $form = $this->createForm(new AulaType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'data' => json_encode($this->getData()),
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

        if (!$entity || $this->get('security.context')->isGranted('ROLE_PROF') && ($entity->getProfessor() != $this->getUser()->getId())) {
            $this->get('session')->getFlashBag()->add('error', 'not authorized');
            return $this->redirect($this->generateUrl('aula'));
        }

        $this->getPresencas($entity);
        $form = $this->createForm(new AulaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $post = $request->request->get($form->getName());

            if ($this->get('security.context')->isGranted('ROLE_ADMIN'))
                $professor = $post['professor'];

            if ($this->get('security.context')->isGranted('ROLE_PROF'))
                $professor = $this->getUser()->getId();

            $entity->setProfessor($professor);

            try {
                list($dia, $mes, $ano) = explode('/', $post['data']);
                $entity->setData(new \DateTime($ano .'-'.$mes .'-'.$dia));
            }
            catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'data invalida!');
                return $this->newAction($entity);
            }

            $entity->setGrupo(isset($post['grupo']) ? $post['grupo'] : NULL);
            $entity->setPresencas(isset($post['presencas']) ? implode(',', $post['presencas']) : NULL);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'aula editada com sucesso!');
            return $this->redirect($this->generateUrl('aula'));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'falha ao editar a aula!');
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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

        $this->get('session')->getFlashBag()->add('success', 'Урок успешно удален!');
        return $this->redirect($this->generateUrl('aula'));
    }

    private function getData()
    {
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

        return $data;
    }

    private function getPresencas(&$entity)
    {
        $presencas = $entity->hasPresencas()
            ? $this->getDoctrine()->getManager()->getRepository('IstEnsinameBundle:Aluno')->createQueryBuilder('a')->where('a.id in ('. $entity->getPresencas() .')')->getQuery()->getResult()
            : array();
        $collection = new ArrayCollection($presencas);
        $entity->setPresencas($collection);
    }
}
