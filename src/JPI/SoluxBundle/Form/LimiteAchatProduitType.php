<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LimiteAchatProduitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbMembreMin', 'integer', array(
            		"label" => "Membres : De",
            		"required" => true
            ))
            ->add('nbMembreMax', 'integer', array(
            		"label" => "À",
            		"required" => true
            ))
            ->add('duree', 'integer', array(
            		"label" => "Durée (Jours)",
            		"required" => true
            ))
            ->add('quantiteMax', 'number', array(
            		"label" => "Quantité maximum",
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
            'data_class' => 'JPI\SoluxBundle\Entity\LimiteAchatProduit'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_limiteachatproduit';
    }
}
