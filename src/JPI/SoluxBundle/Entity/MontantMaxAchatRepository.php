<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MontantMaxAchatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MontantMaxAchatRepository extends EntityRepository
{
	public function validLimite($montantMaxAchat)
	{
		$lQuery = $this
			->createQueryBuilder('a')
			->where(':nbMembreAdulteMin BETWEEN  a.nbMembreAdulteMin AND a.nbMembreAdulteMax
				OR :nbMembreAdulteMax BETWEEN  a.nbMembreAdulteMin AND a.nbMembreAdulteMax
				OR (:nbMembreAdulteMin <= a.nbMembreAdulteMin AND  a.nbMembreAdulteMax <= :nbMembreAdulteMax)')
			->andWhere(':nbMembreEnfantMin BETWEEN  a.nbMembreEnfantMin AND a.nbMembreEnfantMax
				OR :nbMembreEnfantMax BETWEEN  a.nbMembreEnfantMin AND a.nbMembreEnfantMax
				OR (:nbMembreEnfantMin <= a.nbMembreEnfantMin AND  a.nbMembreEnfantMax <= :nbMembreEnfantMax)')			
			->setParameter('nbMembreAdulteMin', $montantMaxAchat->getNbMembreAdulteMin())
			->setParameter('nbMembreAdulteMax', $montantMaxAchat->getNbMembreAdulteMax())
			->setParameter('nbMembreEnfantMin', $montantMaxAchat->getNbMembreEnfantMin())
			->setParameter('nbMembreEnfantMax', $montantMaxAchat->getNbMembreEnfantMax());
		
		$lId = $montantMaxAchat->getId();
		if(!is_null($lId) ) {	
			$lQuery->andWhere(':id != a.id')
			->setParameter('id', $montantMaxAchat->getId());
		}
		
		return $lQuery
			->getQuery()
			->getResult();
	}
}
