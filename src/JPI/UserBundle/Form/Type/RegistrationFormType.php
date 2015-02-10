<?php

namespace JPI\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', 'choice', array('choices' => 
                array(
                    'ROLE_ADMIN' => 'Administrateur',
                ),
        		'label' => 'Droits',
                'required'  => true,
                'multiple' => true,
            	'expanded' => true
            ));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'jpi_user_registration';
    }
}
