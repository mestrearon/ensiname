<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlunoType extends AbstractType
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
            ->add('estudo', 'choice', array(
                'choices' => array('g' => 'в группе', 'i' => 'индивидуально'),
                'required' => true,))
            ->add('fone', 'text')
            ->add('email', 'email')
            ->add('status', 'choice', array(
                'choices' => array('a' => 'активен', 'p' => 'пауза', 'd' => 'не активен', 'f' => 'sair de férias'),
                'required' => true,))
            ->add('observacao')
            ->add('ferias', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ist\Bundle\EnsinameBundle\Entity\Aluno'
        ));
    }

    public function getName()
    {
        return 'ist_bundle_ensinamebundle_alunotype';
    }
}
