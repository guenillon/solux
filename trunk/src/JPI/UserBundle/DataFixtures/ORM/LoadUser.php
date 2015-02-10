<?php
// src/JPI/UserBundle/DataFixtures/ORM/LoadUser.php

namespace JPI\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use JPI\UserBundle\Entity\User;

class LoadUser implements FixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	
	/**
	 * {@inheritDoc}
	 */
  public function load(ObjectManager $manager)
  {
  	
  	$userManager = $this->container->get('fos_user.user_manager');
  	
    // Les noms d'utilisateurs à créer
    $listNames = array('jpi', 'Marine', 'Anna');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = $userManager->createUser();

      // Le nom d'utilisateur et le mot de passe sont identiques
      $user->setUsername($name);
      $user->setPlainPassword($name);
      $user->setEmail($name);
      $user->setEnabled(true);
      // On ne se sert pas du sel pour l'instant
     // $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_USER'));

      // On le persiste
      $manager->persist($user);
    }
    
    $name = 'admin';
    
    // On crée l'utilisateur
      $user = $userManager->createUser();
    
    // Le nom d'utilisateur et le mot de passe sont identiques
    $user->setUsername($name);
    $user->setEmail($name);
    $user->setPlainPassword($name);
    $user->setEnabled(true);
    
    // On ne se sert pas du sel pour l'instant
   // $user->setSalt('');
    // On définit uniquement le role ROLE_USER qui est le role de base
    $user->setRoles(array('ROLE_ADMIN'));
    
    // On le persiste
    $manager->persist($user);

    // On déclenche l'enregistrement
    $manager->flush();
  }
}
?>