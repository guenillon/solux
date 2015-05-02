<?php
namespace JPI\SoluxBundle\Controller;

use JPI\SoluxBundle\Controller\EntityController;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\Categorie;
use JPI\SoluxBundle\Form\Type\CategorieType;

class CategorieController extends EntityController
{
	public function __construct()
	{
		$this->entityClass = new Categorie();
		$this->entityTypeClass = new CategorieType();
		
		$this->entityLabel = "categorie";
		$this->entityName = "Categorie";
		
		$this->pathList = 'jpi_solux_categorie';
		
		$this->repository = 'JPISoluxBundle:Categorie';

		$this->showAttributes = array(
				"header" => array("Nom", "Description"),
				"attribute" => array("nom", "description"));
		
		$this->exportAttributes = array(
				"header" => array("Nom", "Description"),
				"attribute" => array("nom", "description"));
		parent::__construct();
	}
	
	public function showAction($id)
	{
		$entity = $this->getEntity($id);
		$content = $this->getShowAttributes($entity);
	
		return $this->show($entity, $content, true, $entity->getNom());
	}
	
	public function editAction(Request $request, $id)
	{
		$entity = $this->getEntity($id);
		return $this->edit($entity, $request, $this->entityTypeClass, $entity->getNom());
	}
}
?>