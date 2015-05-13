<?php

namespace Hollo\TrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShoutOutType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'choices' => array(
                    'public' => 'Public'
                ),
                'required' => true
            ))
            ->add('content')
            ->add('user')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hollo\TrackerBundle\Entity\ShoutOut'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hollo_trackerbundle_shoutout';
    }
}
