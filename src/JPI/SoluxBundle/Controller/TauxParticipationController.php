<?php
namespace JPI\SoluxBundle\Controller;

use JPI\CoreBundle\Export\Classes\JPIExportConfig;
use JPI\SoluxBundle\Controller\EntityController;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\TauxParticipation;
use JPI\SoluxBundle\Form\TauxParticipationType;

class TauxParticipationController extends EntityController
{
	public function __construct()
	{
		$this->entityClass = new TauxParticipation();
		$this->entityTypeClass = new TauxParticipationType();
		

		$this->entityLabel = "taux_participation";
		$this->entityName = "TauxParticipation";
		
		$this->pathList = 'jpi_solux_taux_participation';

		$this->repository = 'JPISoluxBundle:TauxParticipation';
		
		$this->showAttributes = array(
				"header" => array("min", "max", "taux"), 
				"attribute" => array("min", "max", "taux"));

		$this->exportAttributes = array(
				"header" => array("min", "max", "taux"),
				"attribute" => array("min", "max", "taux"));
		parent::__construct();
	}
	
	public function showAction($id)
	{
		$entity = $this->getEntity($id);
		$content = $this->getShowAttributes($entity);
	
		$template = 'JPISoluxBundle:'.$this->entityName.':show.html.twig';
		return $this->render($template, array(
				"entity" => $entity,
				"pathEdit" => $this->generateUrl($this->pathEdit, array('id' => $entity->getId())),
				"pathDelete" => $this->generateUrl($this->pathDelete, array('id' => $entity->getId())),
				"entityName" => $this->entityLabelShow,
				"entityLabel" => $entity->getId(),
				"taux" => $entity
		));
	}
}
?>