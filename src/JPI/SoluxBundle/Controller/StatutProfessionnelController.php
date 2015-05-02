<?php
namespace JPI\SoluxBundle\Controller;

use JPI\SoluxBundle\Controller\EntityController;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\StatutProfessionnel;
use JPI\SoluxBundle\Form\Type\StatutProfessionnelType;

class StatutProfessionnelController extends EntityController
{
	public function __construct()
	{
		$this->entityClass = new StatutProfessionnel();
		$this->entityTypeClass = new StatutProfessionnelType();
		
		$this->entityLabel = "statut_professionnel";
		$this->entityName = "StatutProfessionnel";
		
		$this->pathList = 'jpi_solux_statut_professionnel';
		
		$this->repository = 'JPISoluxBundle:StatutProfessionnel';

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
	
		return $this->show($entity, $content, false, $entity->getNom());
	}
	
	public function editAction(Request $request, $id)
	{
		$entity = $this->getEntity($id);
		return $this->edit($entity, $request, $this->entityTypeClass, $entity->getNom());
	}
}
?>