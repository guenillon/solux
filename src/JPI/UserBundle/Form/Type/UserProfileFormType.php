<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JPI\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Security\Core\SecurityContext;

class UserProfileFormType extends AbstractType
{
	private $securityContext;

    /**
     * @param string $class The User class name
     */
    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$currentUser = $this->securityContext->getToken()->getUser();
    	$builder->addEventListener(
    			FormEvents::PRE_SET_DATA,
    			function(FormEvent $event) use ($currentUser) {
    				// On récupère notre objet user sous-jacent
    				$user = $event->getData();
    				if (null === $user) {
    					return;
    				}
    				 
    				if($currentUser == $user) {
    					$event->getForm()->remove('roles');
    				}
    			}
    	);
    	
        $builder->remove('plainPassword');
    }

    public function getName()
    {
        return 'jpi_user_profile';
    }
    
    public function getParent()
    {
    	return 'jpi_user_registration';
    }
}
