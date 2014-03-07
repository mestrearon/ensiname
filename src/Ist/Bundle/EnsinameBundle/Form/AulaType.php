<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AulaType extends AbstractType
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $professorOptions = array(
            'class' => 'IstEnsinameBundle:Professor',
            'query_builder' => function(EntityRepository $er) { return $er->createQueryBuilder('u')->orderBy('u.nome', 'ASC'); },
            'property' => 'nome',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
        );

        $professor = $options['data']->getProfessor();

        if ($professor)
            $professorOptions['data'] = $this->em->getReference('IstEnsinameBundle:Professor', $professor);

        $grupoOptions = array(
            'class' => 'IstEnsinameBundle:Grupo',
            'query_builder' => function(EntityRepository $er) { return $er->createQueryBuilder('u')->orderBy('u.titulo', 'ASC'); },
            'property' => 'titulo',
            'required' => true,
            'expanded' => false,
            'multiple' => false,
        );

        $grupo = $options['data']->getGrupo();

        if ($grupo)
            $grupoOptions['data'] = $this->em->getReference('IstEnsinameBundle:Grupo', $grupo);

        $presencasOptions = array(
            'class' => 'IstEnsinameBundle:Aluno',
            'query_builder' => function(EntityRepository $er) { return $er->createQueryBuilder('u')->orderBy('u.nome', 'ASC'); },
            'property' => 'nome',
            'required' => true,
            'expanded' => true,
            'multiple' => true,
        );

        $presencas = $options['data']->getPresencas();

        if ($presencas)
            $presencasOptions['data'] = $presencas;

        $builder
            ->add('professor', 'entity', $professorOptions)
            ->add('data', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('inicio')
            ->add('fim')
            ->add('grupo', 'entity', $grupoOptions)
            ->add('presencas', 'entity', $presencasOptions)
            ->add('dada', 'choice', array(
                'choices' => array('s' => 'проведен', 'n' => 'отменен'),
                'required' => true,
            ))
            ->add('observacao')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ist\Bundle\EnsinameBundle\Entity\Aula'
        ));
    }

    public function getName()
    {
        return 'ist_bundle_ensinamebundle_aulatype';
    }
}
