<?php
namespace JPI\SoluxBundle\Manager;

use Doctrine\ORM\EntityManager;

abstract class BaseManager
{
	
	protected $em;
	protected $entity;
	protected $entityTypeClass;
	protected $repository;
	protected $entityTranslateLabel;
	protected $entityDisplayLabel;
	protected $pathList;
	protected $showAttributes;
	protected $exportAttributes;
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->showAttributes = array("header" => array("Id"), "attribute" => array("id"));
		$this->exportAttributes = array("header" => array("Id"), "attribute" => array("id"));
	}
	
	public function setEntity($entity)
	{
		$this->entity = $entity;
		
		return $this;
	}
	
	public function getEntity()
	{
		return $this->entity;
	}
	
	public function setEntityTypeClass($entityTypeClass)
	{
		$this->entityTypeClass = $entityTypeClass;
	
		return $this;
	}
	
	public function getEntityTypeClass()
	{
		return $this->entityTypeClass;
	}
	
	public function setEm($em)
	{
		$this->em = $em;
	
		return $this;
	}
	
	public function getEm()
	{
		return $this->em;
	}
	
	public function setEntityTranslateLabel($entityTranslateLabel)
	{
		$this->entityTranslateLabel = $entityTranslateLabel;
	
		return $this;
	}
	
	public function getEntityTranslateLabel()
	{
		return $this->entityTranslateLabel;
	}
	
	public function setEntityDisplayLabel($entityDisplayLabel)
	{
		$this->entityDisplayLabel = $entityDisplayLabel;
	
		return $this;
	}
	
	public function getEntityDisplayLabel()
	{
		return $this->entityDisplayLabel;
	}
	
	public function setPathList($pathList)
	{
		$this->pathList = $pathList;
	
		return $this;
	}
	
	public function getPathList()
	{
		return $this->pathList;
	}

	public function setShowAttributes($showAttributes)
	{
		$this->showAttributes = $showAttributes;
	
		return $this;
	}
	
	public function getShowAttributes()
	{
		return $this->showAttributes;
	}
	
	public function setExportAttributes($exportAttributes)
	{
		$this->exportAttributes = $exportAttributes;
	
		return $this;
	}
	
	public function getExportAttributes()
	{
		return $this->exportAttributes;
	}

	protected function persistAndFlush($entity)
	{
		$this->em->persist($entity);
		$this->em->flush();
	}
	
	protected function getRepository($repository = NULL)
	{
		if(is_null($repository))
		{
			$repository = $this->repository;
		}
		return $this->em->getRepository($repository);
	}
	
	protected function deleteEntity($entity)
	{
		$this->em->remove($entity);
		$this->em->flush();
	}
	
	public function getListe()
	{
		return $this->getRepository()->findAll();
	}
}
?>
