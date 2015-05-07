<?php
namespace JPI\SoluxBundle\Controller;

use JPI\SoluxBundle\Controller\EntityController;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\Produit;
use JPI\SoluxBundle\Form\Type\ProduitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProduitController extends EntityController
{
	public function __construct()
	{
		$this->entityClass = new Produit();
		$this->entityTypeClass = new ProduitType();
		

		$this->entityLabel = "produit";
		$this->entityName = "Produit";
		
		$this->pathList = 'jpi_solux_produit';

		$this->repository = 'JPISoluxBundle:Produit';
		
		$this->showAttributes = array(
				"header" => array("Nom", "Quantité", "Unité", "Prix", "Prix Fixe ?", "Catégorie"), 
				"attribute" => array("nom", "quantite", "unite", "prix", "prixFixe", "categorie"));

		$this->exportAttributes = array(
				"header" => array("Nom", "Description", "Quantité", "Unité", "Prix", "Prix Fixe ?"),
				"attribute" => array("nom", "description", "quantite", "unite", "prix", "prixFixe"));
		parent::__construct();
	}
	
	public function showAction($id)
	{
		$entity = $this->getEntity($id);
		$template = 'JPISoluxBundle:'.$this->entityName.':show.html.twig';

		$lProduit = $entity[0];
		$id = $lProduit->getId();
		return $this->render($template, array(
				"pathEdit" => $this->generateUrl($this->pathEdit, array('id' => $id)),
				"pathDelete" => $this->generateUrl($this->pathDelete, array('id' => $id)),
				"entityName" => $this->entityLabelShow,
				"entityLabel" => $lProduit->getNom(),
				"produit" => $lProduit
		));
	}
	
	public function editAction(Request $request, $id)
	{
		$entity = $this->getEntity($id);
		$lProduit = $entity[0];
		return $this->edit($lProduit, $request, $this->entityTypeClass, $lProduit->getNom());
	}
	
	protected function getEntity($id) {
		$entity = $this->getRepository()->getProduit($id);
		if (null === $entity) {
			throw new NotFoundHttpException("L'élément d'id ".$id." n'existe pas.");
		}
		return $entity;
	}
}
?>
