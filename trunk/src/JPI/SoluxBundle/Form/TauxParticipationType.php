<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TauxParticipationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('min', 'money', array(
            		"label" => "Reste pour vivre : De",
            		"required" => true
            ))
            ->add('max', 'money', array(
            		"label" => "À",
            		"required" => true
            ))
            ->add('taux', 'percent', array(
            		"precision" => 2,
            		"required" => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JPI\SoluxBundle\Entity\TauxParticipation',
        	'error_mapping' => array(
        		'minValid' => 'min',
        		'maxValid' => 'max'
        	)
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_tauxparticipation';
    }
}
