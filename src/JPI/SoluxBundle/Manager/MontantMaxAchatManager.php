<?php
namespace JPI\SoluxBundle\Manager;

use JPI\SoluxBundle\Manager\BaseManager;
use JPI\SoluxBundle\Entity\MontantMaxAchat;
use JPI\SoluxBundle\Form\Type\MontantMaxAchatType;
use Doctrine\ORM\EntityManager;

class MontantMaxAchatManager extends BaseManager
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em);
		$this->repository = 'JPISoluxBundle:MontantMaxAchat';
		$this->entity = new MontantMaxAchat();
		$this->entityTypeClass = new MontantMaxAchatType();
		
		
		$this->entityTranslateLabel = "montant_max_achat";
		$this->entityDisplayLabel = "MontantMaxAchat";
		$this->pathList = 'jpi_solux_montant_max_achat';
		
		$this->exportAttributes = array(
				"header" => array("Adultes : De", "À", "Enfants : De", "À", "Durée", "Montant"),
				"attribute" => array("nbMembreAdulteMin", "nbMembreAdulteMax", "nbMembreEnfantMin", "nbMembreEnfantMax", "duree", "montant"));
	}
	
	public function set(MontantMaxAchat $montantMax)
	{
		return $this->persistAndFlush($montantMax);
	}
	
	public function delete(MontantMaxAchat $montantMax)
	{
		return $this->deleteEntity($montantMax);
	}
}
?>
