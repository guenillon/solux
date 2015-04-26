<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MontantMaxAchatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbMembreAdulteMin', 'integer', array(
            		"label" => "Adultes : De",
            		"required" => true
            ))
            ->add('nbMembreAdulteMax', 'integer', array(
            		"label" => "À",
            		"required" => true
            ))
            ->add('nbMembreEnfantMin', 'integer', array(
            		"label" => "Enfants : De",
            		"required" => true
            ))
            ->add('nbMembreEnfantMax', 'integer', array(
            		"label" => "À",
            		"required" => true
            ))
            ->add('duree', 'integer', array(
            		"label" => "Durée (Jours)",
            		"required" => true
            ))
            ->add('montant', 'money', array(
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
            'data_class' => 'JPI\SoluxBundle\Entity\MontantMaxAchat'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_montantmaxachat';
    }
}
