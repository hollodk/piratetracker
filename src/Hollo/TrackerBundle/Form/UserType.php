<?php

namespace Hollo\TrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('rank')
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('fraction')
            ->add('admin', 'checkbox', array(
                'required' => false
            ))
            ->add('mapFollow', 'checkbox', array(
                'required' => false
            ))
            ->add('icon')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hollo\TrackerBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hollo_trackerbundle_user';
    }
}
