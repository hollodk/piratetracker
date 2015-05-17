<?php

namespace Hollo\TrackerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FractionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $icons = array(
            'marker-green.png' => 'Green',
            'marker-red.png' => 'Red',
            'marker-yellow.png' => 'Yellow',
            'marker-blue.png' => 'Blue'
        );

        $builder
            ->add('name')
            ->add('icon', 'choice', array(
                'choices' => $icons
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hollo\TrackerBundle\Entity\Fraction'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hollo_trackerbundle_fraction';
    }
}
