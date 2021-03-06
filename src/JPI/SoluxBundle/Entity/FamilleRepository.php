<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FamilleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FamilleRepository extends EntityRepository
{
	public function getTauxParticipation($id)
	{	
		$lQuery = $this->_em->createQueryBuilder();
		
		$lSubquery1 = $this->createQueryBuilder('a');
		$lSubquery1->select( '(' . $lQuery->expr()->diff('a.recettes', 'a.depenses') .') / sum(membres.pourcentageACharge)' )
			->JOIN('a.membres', 'membres')
			->where($lQuery->expr()->eq('a.id', ':id'));
		
		$lSubquery2 = $this->createQueryBuilder('c');
		$lSubquery2->select( '(' . $lQuery->expr()->diff('c.recettes', 'c.depenses') .') / sum(membres2.pourcentageACharge)' )
		->JOIN('c.membres', 'membres2')
		->where($lQuery->expr()->eq('c.id', ':id'));

		$lQuery->select('b')
			->from('JPI\SoluxBundle\Entity\TauxParticipation', 'b')
			->Where($lQuery->expr()->gte(sprintf('(%s)', $lSubquery1->getDQL()), 'b.min'))
			->andWhere($lQuery->expr()->lte(sprintf('(%s)', $lSubquery2->getDQL()), 'b.max'))
			->setParameter('id', $id);

		return $lQuery
			->getQuery()
			->getOneOrNullResult();
	}
}
