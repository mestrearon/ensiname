<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfessorType extends AbstractType
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
            ->add('fone', 'text')
            ->add('email', 'email')
            ->add('horario', 'text')
            ->add('observacao')
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
