<?php

namespace JPI\SoluxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AchatDetailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite', 'number', array(
            		"label" => "Quantité",
            		"required" => true
            ))
            ->add('unite', 'text', array(
            		"label" => "Unité",
            		"required" => true
            ))
            ->add('prix', 'money', array(
            		"required" => true
            ))

            ->add('taux', 'text', array(
            		"required" => true
            ))
            ->add('prixPaye', 'money', array(
            		"required" => true
            ))
            ->add('produit', 'entity', array(
            		"label" => "Produit",
            		"class" => "JPISoluxBundle:Produit",
            		'property' => 'id',
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
            'data_class' => 'JPI\SoluxBundle\Entity\AchatDetail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_achatdetail';
    }
}
