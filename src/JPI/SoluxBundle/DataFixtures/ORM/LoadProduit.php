<?php
namespace JPI\SoluxBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JPI\soluxBundle\Entity\Produit;

class LoadProduit implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listeProduits = array(
    	array("Patate","C'est de la pomme de terre", 123123, 1.0, "Kg", 1.25, false),
    	array("Oeuf","Vive les poulettes", 987654321, 2.25, "Douzaine", 6, true),    		
    );

    foreach ($listeProduits as $lProduitData) {
      $produit = new Produit;

      $produit->setNom($lProduitData[0]);
      $produit->setDescription($lProduitData[1]);
      $produit->setCodeBarre($lProduitData[2]);
      $produit->setQuantite($lProduitData[3]);
      $produit->setUnite($lProduitData[4]);
      $produit->setPrix($lProduitData[5]);
      $produit->setPrixFixe($lProduitData[6]);
      // On le persiste
      $manager->persist($produit);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}