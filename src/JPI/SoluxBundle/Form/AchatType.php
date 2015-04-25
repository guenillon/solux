<?php

namespace JPI\SoluxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
/*use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;*/

class AchatType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('detail', 'collection', array(
            		"label" => "Produits",
            		'type'         => new AchatDetailType(),
            		'allow_add'    => true,
            		'allow_delete' => true,
            		'by_reference' => false
            ))
        ;
            
          /*  $builder->addEventListener(
            		FormEvents::POST_SUBMIT,
            		function(FormEvent $event) {
            			// Il est important de récupérer ici $event->getForm()->getData(),
            			// car $event->getData() vous renverra la données initiale (vide)
            			$achat = $event->getForm()->getData();
            			$achat->majDetail();
            			
            		}
            );*/
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
