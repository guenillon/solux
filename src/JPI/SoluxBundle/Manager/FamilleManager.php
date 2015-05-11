<?php
namespace JPI\SoluxBundle\Manager;

use JPI\SoluxBundle\Manager\BaseManager;
use JPI\SoluxBundle\Entity\Famille;
use JPI\SoluxBundle\Form\Type\FamilleType;
use Doctrine\ORM\EntityManager;

class FamilleManager extends BaseManager
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em);
		$this->repository = 'JPISoluxBundle:Famille';
		$this->entity = new Famille();
		$this->entityTypeClass = new FamilleType();
		
		$this->entityTranslateLabel = "famille";
		$this->entityDisplayLabel = "Famille";
		$this->pathList = 'jpi_solux_famille';

		$this->exportAttributes = array(
				"header" => array("Nom", "Prénom"),
				"attribute" => array("nom", "prenomChef"));
	}
	
	public function set(Famille $famille)
	{
		return $this->persistAndFlush($famille);
	}
	
	public function delete(Famille $famille)
	{
		return $this->deleteEntity($famille);
	}
}
?>
