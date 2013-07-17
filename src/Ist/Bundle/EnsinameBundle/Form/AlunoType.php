<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlunoType extends AbstractType
{
    protected $linguas;

    function __construct($linguas)
    {
        $this->linguas = $linguas;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('nascimento', 'text')
            ->add('linguas', 'choice', array(
                'choice_list' => new LinguaChoiceList($this->linguas),
                'multiple'  => true,
                'expanded'  => true,
            ))
            ->add('estudo', 'choice', array(
                'choices' => array('g' => 'в группе', 'i' => 'индивидуально'),
                'required' => true,))
            ->add('fone', 'text')
            ->add('email', 'email')
            ->add('status', 'choice', array(
                'choices' => array('a' => 'активен', 'p' => 'пауза', 'd' => 'не активен'),
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
