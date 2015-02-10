<?php
namespace JPI\SoluxBundle\Controller;

use JPI\CoreBundle\Export\Classes\JPIExportConfig;
use JPI\SoluxBundle\Controller\EntityController;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\Produit;
use JPI\SoluxBundle\Form\ProduitType;

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
		
		$translator = $this->get('translator');
		if($entity->getPrixFixe()) {
			$PrixFixe = $translator->trans('produit.show.prixFixe.oui');
		} else {
			$PrixFixe = $translator->trans('produit.show.prixFixe.non');			
		}
		
		$template = 'JPISoluxBundle:'.$this->entityName.':show.html.twig';

		return $this->render($template, array(
				"entity" => $entity,
				"pathEdit" => $this->generateUrl($this->pathEdit, array('id' => $entity->getId())),
				"pathDelete" => $this->generateUrl($this->pathDelete, array('id' => $entity->getId())),
				"entityName" => $this->entityLabelShow,
				"entityLabel" => $entity->getNom(),
				"produit" => $entity,
				"prixFixe" => $PrixFixe
		));
	}
	
	public function editAction(Request $request, $id)
	{
		$entity = $this->getEntity($id);
		return $this->edit($entity, $request, $this->entityTypeClass, $entity->getNom());
	}
}
?>