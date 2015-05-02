<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MembreFamilleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array(
            		"label" => "Nom",
            		"required" => true
            ))
            ->add('prenom', 'text', array(
            		"label" => "Prénom",
            		"required" => true
            ))
            ->add('dateNaissance', 'birthday',  array(
            		"label" => "Date de Naissance"
            ))
            ->add('pourcentageACharge', 'percent', array(
            		"label" => "% à charge",
            		"precision" => 2,
            		"required" => false
            ))
            ->add('parent', 'checkbox', array(
            		"label" => "Parent",
            		"required" => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JPI\SoluxBundle\Entity\MembreFamille'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_membrefamille';
    }
}
