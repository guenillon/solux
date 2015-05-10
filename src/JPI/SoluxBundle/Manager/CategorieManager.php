<?php
namespace JPI\SoluxBundle\Manager;

use JPI\SoluxBundle\Manager\BaseManager;
use JPI\SoluxBundle\Entity\Categorie;
use JPI\SoluxBundle\Form\Type\CategorieType;
use Doctrine\ORM\EntityManager;

class CategorieManager extends BaseManager
{
	public function __construct(EntityManager $em)
	{
		parent::__construct($em);
		$this->repository = 'JPISoluxBundle:Categorie';
		$this->entity = new Categorie();
		$this->entityTypeClass = new CategorieType();
		
		
		$this->entityTranslateLabel = "categorie";
		$this->entityDisplayLabel = "Categorie";
		$this->pathList = 'jpi_solux_categorie';
		
		$this->showAttributes = array(
				"header" => array("Nom", "Description"),
				"attribute" => array("nom", "description"));
		
		$this->exportAttributes = array(
				"header" => array("Nom", "Description"),
				"attribute" => array("nom", "description"));
	}
	
	/**
	 * Retourne la liste des catégories
	 *
	 * @return Categorie
	 */
	public function getListe()
	{
		return $this->getRepository()->findAll();
	}

	public function set(Categorie $categorie)
	{
		return $this->persistAndFlush($categorie);
	}
	
	public function delete(Categorie $categorie)
	{
		return $this->deleteEntity($categorie);
	}
}
?>
