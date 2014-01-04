<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('lingua', 'entity', array(
                'class' => 'IstEnsinameBundle:Lingua',
                'property' => 'titulo',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('professor', 'entity', array(
                'class' => 'IstEnsinameBundle:Professor',
                'property' => 'nome',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('alunos', 'entity', array(
                'class' => 'IstEnsinameBundle:Aluno',
                'property' => 'nome',
                'query_builder' => function(EntityRepository $er) { return $er->createQueryBuilder('u')->orderBy('u.nome', 'ASC'); },
                'required' => true,
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('horario')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ist\Bundle\EnsinameBundle\Entity\Grupo'
        ));
    }

    public function getName()
    {
        return 'ist_bundle_ensinamebundle_grupotype';
    }
}
