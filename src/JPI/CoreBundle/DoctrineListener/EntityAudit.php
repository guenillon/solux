<?php

namespace JPI\CoreBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EntityAudit
{	
	protected $container;
	private $token_storage;
	
	public function __construct(TokenStorageInterface $token_storage) {
		$this->token_storage = $token_storage;
	}
	
	public function preUpdate(LifecycleEventArgs $args)
  	{
  		$lUser = $this->token_storage->getToken()->getUser();
    	$entity = $args->getEntity();
    	$entity->setUpdatedAt(new \Datetime());
    	$entity->setUpdatedBy($lUser->getId());
	}
	
	public function prePersist(LifecycleEventArgs $args)
	{
		$lUser = $this->token_storage->getToken()->getUser();
		$entity = $args->getEntity();
		$entity->setcreatedAt(new \Datetime());
		$entity->setcreatedBy($lUser->getId());
	}
}
