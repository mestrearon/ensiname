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
            ->add('nascimento', 'birthday', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'yyyy-MM-dd',))
            //->add('linguas')
            ->add('estudo', 'choice', array(
                'choices' => array('g' => 'grupo', 'i' => 'individual'),
                'required' => true,))
            ->add('fone')
            ->add('email', 'email')
            ->add('status', 'choice', array(
                'choices' => array('a' => 'ativo', 'p' => 'pausa', 'd' => 'desativo'),
                'required' => true,))
            ->add('observacao')
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
