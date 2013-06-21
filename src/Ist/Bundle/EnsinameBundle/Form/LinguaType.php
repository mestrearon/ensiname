<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LinguaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('observacao')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ist\Bundle\EnsinameBundle\Entity\Lingua'
        ));
    }

    public function getName()
    {
        return 'ist_bundle_ensinamebundle_linguatype';
    }
}
