<?php
namespace JPI\SoluxBundle\Manager;

use JPI\SoluxBundle\Manager\BaseManager;
use JPI\SoluxBundle\Entity\TauxParticipation;
use JPI\SoluxBundle\Form\Type\TauxParticipationType;
use Doctrine\ORM\EntityManager;

class TauxParticipationManager extends BaseManager
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em);
		$this->repository = 'JPISoluxBundle:TauxParticipation';
		$this->entity = new TauxParticipation();
		$this->entityTypeClass = new TauxParticipationType();
		
		
		$this->entityTranslateLabel = "taux_participation";
		$this->entityDisplayLabel = "TauxParticipation";
		$this->pathList = 'jpi_solux_taux_participation';
		
		$this->showAttributes = array(
				"header" => array("min", "max", "taux"), 
				"attribute" => array("min", "max", "taux"));

		$this->exportAttributes = array(
				"header" => array("min", "max", "taux"),
				"attribute" => array("min", "max", "taux"));
	}
	
	public function set(TauxParticipation $tauxParticipation)
	{
		return $this->persistAndFlush($tauxParticipation);
	}
	
	public function delete(TauxParticipation $tauxParticipation)
	{
		return $this->deleteEntity($tauxParticipation);
	}
}
?>
