<?php
namespace JPI\SoluxBundle\Controller;

use JPI\CoreBundle\Export\Classes\JPIExportConfig;
use JPI\SoluxBundle\Controller\EntityController;
use Symfony\Component\HttpFoundation\Request;
use JPI\SoluxBundle\Entity\MontantMaxAchat;
use JPI\SoluxBundle\Form\MontantMaxAchatType;

class MontantMaxAchatController extends EntityController
{
	public function __construct()
	{
		$this->entityClass = new MontantMaxAchat();
		$this->entityTypeClass = new MontantMaxAchatType();
		
		$this->entityLabel = "montant_max_achat";
		$this->entityName = "MontantMaxAchat";
		
		$this->pathList = 'jpi_solux_montant_max_achat';
		
		$this->repository = 'JPISoluxBundle:MontantMaxAchat';

		/*$this->showAttributes = array(
				"header" => array("Nom", "Description"),
				"attribute" => array("nom", "description"));*/
		
		$this->exportAttributes = array(
				"header" => array("Adultes : De", "À", "Enfants : De", "À", "Durée", "Montant"),
				"attribute" => array("nbMembreAdulteMin", "nbMembreAdulteMax", "nbMembreEnfantMin", "nbMembreEnfantMax", "duree", "montant"));
		parent::__construct();
	}
	
	public function showAction($id)
	{
		$entity = $this->getEntity($id);		
		$template = 'JPISoluxBundle:'.$this->entityName.':show.html.twig';

		return $this->render($template, array(
				"entity" => $entity,
				"pathEdit" => $this->generateUrl($this->pathEdit, array('id' => $entity->getId())),
				"pathDelete" => $this->generateUrl($this->pathDelete, array('id' => $entity->getId())),
				"entityName" => $this->entityLabelShow,
				"entityLabel" => $entity->getId(),
				"montant" => $entity
		));
	}
}
?>