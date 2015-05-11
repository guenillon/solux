<?php
namespace JPI\SoluxBundle\Manager;

use JPI\SoluxBundle\Manager\BaseManager;
use JPI\SoluxBundle\Entity\Produit;
use JPI\SoluxBundle\Form\Type\ProduitType;
use Doctrine\ORM\EntityManager;

class ProduitManager extends BaseManager
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em);
		$this->repository = 'JPISoluxBundle:Produit';
		$this->entity = new Produit();
		$this->entityTypeClass = new ProduitType();
		
		
		$this->entityTranslateLabel = "produit";
		$this->entityDisplayLabel = "Produit";
		$this->pathList = 'jpi_solux_produit';
		
		$this->showAttributes = array(
				"header" => array("Nom", "Quantité", "Unité", "Prix", "Prix Fixe ?", "Catégorie"), 
				"attribute" => array("nom", "quantite", "unite", "prix", "prixFixe", "categorie"));

		$this->exportAttributes = array(
				"header" => array("Nom", "Description", "Quantité", "Unité", "Prix", "Prix Fixe ?"),
				"attribute" => array("nom", "description", "quantite", "unite", "prix", "prixFixe"));
	}
	
	public function set(Produit $produit)
	{
		return $this->persistAndFlush($produit);
	}
	
	public function delete(Produit $produit)
	{
		return $this->deleteEntity($produit);
	}
}
?>
