<?php

namespace JPI\SoluxBundle\Form;

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
            ->add('quantite')
            ->add('unite')
            ->add('prix')
            ->add('produit')
            ->add('produit', 'integer', array(
            		"label" => "Produit",
            		/*"class" => "JPISoluxBundle:Produit",
            		'property' => 'nom',*/
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
