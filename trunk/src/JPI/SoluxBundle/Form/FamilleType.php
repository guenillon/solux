<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FamilleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenomChef')
            ->add('dateEntree')
            ->add('dateSortie')
            ->add('recettes', 'money', array(
            		"required" => true
            ))
            ->add('depenses', 'money', array(
            		"required" => true
            ))
            ->add('statutProfessionnel', 'entity', array(
            		"class" => "JPISoluxBundle:StatutProfessionnel",
  					'property' => 'nom',
            		"required" => true
            ))
            ->add('membres', 'collection', array(
            		'type'         => new MembreFamilleType(),
            		'allow_add'    => true,
            		'allow_delete' => true,
            		'by_reference' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JPI\SoluxBundle\Entity\Famille'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_famille';
    }
}
