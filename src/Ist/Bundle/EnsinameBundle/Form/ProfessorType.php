<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfessorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('nascimento', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('linguas', 'entity', array(
                'class' => 'IstEnsinameBundle:Lingua',
                'property' => 'titulo',
                'required' => true,
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('fone', 'text')
            ->add('email', 'email')
            ->add('horario', 'text')
            ->add('observacao')
            ->add('username')
            ->add('password')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ist\Bundle\EnsinameBundle\Entity\Professor'
        ));
    }

    public function getName()
    {
        return 'ist_bundle_ensinamebundle_professortype';
    }
}
