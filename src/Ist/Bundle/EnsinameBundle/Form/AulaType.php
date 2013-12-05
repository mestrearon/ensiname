<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AulaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('professor', 'entity', array(
                'class' => 'IstEnsinameBundle:Professor',
                'property' => 'nome',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('data', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('grupo', 'entity', array(
                'class' => 'IstEnsinameBundle:Grupo',
                'property' => 'titulo',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('presencas', 'entity', array(
                'class' => 'IstEnsinameBundle:Aluno',
                'property' => 'nome',
                'required' => true,
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('dada', 'choice', array(
                'choices' => array('s' => 'проведен', 'n' => 'отменен'),
                'required' => true,))
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
