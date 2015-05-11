<?php
namespace JPI\SoluxBundle\Manager;

use JPI\SoluxBundle\Manager\BaseManager;
use JPI\SoluxBundle\Entity\StatutProfessionnel;
use JPI\SoluxBundle\Form\Type\StatutProfessionnelType;
use Doctrine\ORM\EntityManager;

class StatutProfessionnelManager extends BaseManager
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em);
		$this->repository = 'JPISoluxBundle:StatutProfessionnel';
		$this->entity = new StatutProfessionnel();
		$this->entityTypeClass = new StatutProfessionnelType();
		
		$this->entityTranslateLabel = "statut_professionnel";
		$this->entityDisplayLabel = "StatutProfessionnel";
		$this->pathList = 'jpi_solux_statut_professionnel';

		$this->showAttributes = array(
				"header" => array("Nom", "Description"),
				"attribute" => array("nom", "description"));
		
		$this->exportAttributes = array(
				"header" => array("Nom", "Description"),
				"attribute" => array("nom", "description"));
	}
	
	public function set(StatutProfessionnel $statutProfessionnel)
	{
		return $this->persistAndFlush($statutProfessionnel);
	}
	
	public function delete(StatutProfessionnel $statutProfessionnel)
	{
		return $this->deleteEntity($statutProfessionnel);
	}
}
?>
