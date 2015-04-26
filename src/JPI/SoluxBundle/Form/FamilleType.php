<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JPI\SoluxBundle\Entity\StatutProfessionnelRepository;

class FamilleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array(
            		"label" => "Nom"
            ))
            ->add('prenomChef', 'text', array(
            		"label" => "Prénom"
            ))
            ->add('dateEntree', 'date', array(
            		"label" => "Date d'entrée"
			))
            ->add('dateSortie', 'date', array(
            		"required" => false,
			    	"empty_value" => '',
            		"label" => "Date de sortie"
			))
            ->add('recettes', 'money', array(
            		"label" => "Recettes",
            		"required" => true
            ))
            ->add('depenses', 'money', array(
            		"label" => "Dépenses",
            		"required" => true
            ))
            ->add('statutProfessionnel', 'entity', array(
            		"label" => "Statut Professionnel",
            		"class" => "JPISoluxBundle:StatutProfessionnel",
  					'property' => 'nom',
            		"required" => true,
            		'query_builder' => function(StatutProfessionnelRepository $repo) {
            			return $repo->getOrderedQueryBuilder();
            		}
            ))
            ->add('membres', 'collection', array(
            		"label" => "Membres",
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
