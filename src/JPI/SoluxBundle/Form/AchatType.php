<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AchatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('montant', 'money', array(
            		"required" => true
            ))
            ->add('montantPaye', 'money', array(
            		"required" => true
            ))
            ->add('detail', 'collection', array(
            		"label" => "Produits",
            		'type'         => new AchatDetailType(),
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
            'data_class' => 'JPI\SoluxBundle\Entity\Achat'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_achat';
    }
}
