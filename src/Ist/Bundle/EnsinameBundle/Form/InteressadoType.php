<?php

namespace Ist\Bundle\EnsinameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InteressadoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('fone')
            ->add('email', 'email')
            ->add('linguas', 'entity', array(
                'class' => 'IstEnsinameBundle:Lingua',
                'property' => 'titulo',
                'required' => true,
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('fonte')
            ->add('estudo', 'choice', array(
                'choices' => array(
                    'g' => 'в группе',
                    'i' => 'индивидуально',
                    's' => 'skype',
                ),
                'required' => true,
            ))
            ->add('inicio', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('horario')
            ->add('chamada', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
            ))
            ->add('status', 'choice', array(
                'choices' => array(
                    'l' => 'ligou',
                    'c' => 'concordou',
                    'd' => 'discordou',
                    'r' => 'recontactar',
                    'd' => 'desinteressou',
                    'i' => 'iniciou',
                ),
                'required' => true,
            ))
            ->add('observacao')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ist\Bundle\EnsinameBundle\Entity\Interessado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ist_bundle_ensinamebundle_interessado';
    }
}
