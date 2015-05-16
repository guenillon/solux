<?php
namespace JPI\SoluxBundle\Manager;

use JPI\SoluxBundle\Entity\Achat;
use JPI\SoluxBundle\Entity\Famille;
use Doctrine\ORM\EntityManager;

class AchatManager
{
	protected $em;
	
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->repository = 'JPISoluxBundle:Achat';
	}
	
	protected function getRepository($repository = NULL)
	{
		if(is_null($repository))
		{
			$repository = $this->repository;
		}
		return $this->em->getRepository($repository);
	}
	
	public function getTotalAchatProduitSurPeriode($idFamille, $duree, $idProduit)
	{
		return $this->getRepository()->getTotalAchatProduitSurPeriode($idFamille, $duree, $idProduit);
	}
	
	public function set(Achat $achat)
	{
		return $this->persistAndFlush($achat);
	}
	
	protected function persistAndFlush($entity)
	{
		$this->em->persist($entity);
		$this->em->flush();
	}
	
	public function delete(Achat $achat)
	{
		return $this->deleteEntity($achat);
	}
	
	protected function deleteEntity($achat)
	{
		$this->em->remove($achat);
		$this->em->flush();
	}
	
	public function getMontantMax(Famille $famille)
	{
		$repository = $this->getRepository('JPISoluxBundle:MembreFamille');
		$montantMaxAchatParam = $repository->getMontantMax($famille->getId());
		
		$lMontantMaxActuel = null;
		if(!is_null($montantMaxAchatParam)) {
			$repository = $this->getRepository();
			$totalAchat = $repository->getTotalAchatSurPeriode($famille->getId(), $montantMaxAchatParam->getDuree());
			 
			$lMontantMaxActuel = $montantMaxAchatParam->getMontant() - $totalAchat['total'];
			$lMontantMaxActuel = ( $lMontantMaxActuel < 0 ) ? 0 : $lMontantMaxActuel;
		}
		return $lMontantMaxActuel;
	}
	
	public function findByFamille(Famille $famille)
	{
		return $this->getRepository()->findByFamille($famille);
	}
}
?>
