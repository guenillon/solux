<?php

namespace JPI\SoluxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JPI\SoluxBundle\Form\Type\LimiteAchatProduitType;
use JPI\SoluxBundle\Entity\CategorieRepository;

class ProduitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie' , 'entity', array(
            		"label" => "Catégorie",
            		"class" => "JPISoluxBundle:Categorie",
  					'property' => 'nom',
            		"required" => true,
            		'query_builder' => function(CategorieRepository $repo) {
            			return $repo->getOrderedQueryBuilder();
            		}
            ))
            ->add('nom', 'text', array(
            		"required" => true
            ))
            ->add('description', 'textarea', array(
            		"required" => false
            ))
            ->add('codeBarre', 'text', array(
            		"required" => false
            ))
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
            ->add('prixFixe', 'checkbox', array(
            		"required" => false
            ))
            ->add('limites', 'collection', array(
            		'type'         => new LimiteAchatProduitType(),
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
            'data_class' => 'JPI\SoluxBundle\Entity\Produit'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jpi_soluxbundle_produit';
    }
}
