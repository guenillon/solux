<?php

namespace JPI\CoreBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityAudit
{	
	protected $container;
	
	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	public function preUpdate(LifecycleEventArgs $args)
  	{
  		$lUser = $this->container->get('security.context')->getToken()->getUser();
    	$entity = $args->getEntity();
    	$entity->setUpdatedAt(new \Datetime());
    	$entity->setUpdatedBy($lUser->getId());
	}
	
	public function prePersist(LifecycleEventArgs $args)
	{
		$lUser = $this->container->get('security.context')->getToken()->getUser();
		$entity = $args->getEntity();
		$entity->setcreatedAt(new \Datetime());
		$entity->setcreatedBy($lUser->getId());
	}
}
