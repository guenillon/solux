<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TauxParticipationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TauxParticipationRepository extends EntityRepository
{
	public function validLimite($tauxParticipation)
	{
		$lQuery = $this
			->createQueryBuilder('a')
			->where(':min BETWEEN  a.min AND a.max
				OR :max BETWEEN  a.min AND a.max
				OR (:min <= a.min AND  a.max <= :max)')
			->setParameter('min', $tauxParticipation->getMin())
			->setParameter('max', $tauxParticipation->getMax());
	
		$lId = $tauxParticipation->getId();
		if(!is_null($lId) ) {
			$lQuery->andWhere(':id != a.id')
			->setParameter('id', $lId);
		}
	
		return $lQuery
		->getQuery()
		->getResult();
	}

	public function familleTauxParticipationExiste($famille)
	{
		$lQuery = $this->createQueryBuilder('a');		
		$lQuery->where($lQuery->expr()->gte( $lQuery->expr()->quot($lQuery->expr()->diff(':recettes', ':depenses') , ':pctAchrg'), 'a.min'))
		->andWhere($lQuery->expr()->lte($lQuery->expr()->quot($lQuery->expr()->diff(':recettes', ':depenses') , ':pctAchrg'), 'a.max'))
		->setParameter('recettes', $famille->getRecettes())
		->setParameter('depenses', $famille->getDepenses())
		->setParameter('pctAchrg', $famille->sumPourcentageACharge());
	
		return $lQuery
		->getQuery()
		->getOneOrNullResult();
	}
}
