<?php

namespace JPI\SoluxBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends EntityRepository
{
	public function getProduit($id)
	{
		$lQuery = $this
		->createQueryBuilder('a')
		->where('a.id = :id')
		->setParameter('id', $id)
		->leftJoin('a.limites', 'limites')
		->addSelect('limites')
		->join('a.categorie', 'categorie')
		->addSelect('categorie')
		->orderBy('limites.nbMembreMin', 'ASC');
		
		return $lQuery
		->getQuery()
		->getResult()
		;
	}
	
	public function getProduits($id)
	{
		$lQuery = $this
		->createQueryBuilder('a')
		->where('a.id in (:id)')
		->setParameter('id', $id)
		->leftJoin('a.limites', 'limites')
		->addSelect('limites')
		->join('a.categorie', 'categorie')
		->addSelect('categorie')
		->orderBy('limites.nbMembreMin', 'ASC');
	
		return $lQuery
		->getQuery()
		->getResult()
		;
	}
	
	public function findProduitByParametres($data)
	{
		$query = $this->createQueryBuilder('a');
			
		// Si la recherche porte sur le codeBarre	
		if($data['codeBarre'] != '')
		{
			$query->andWhere('a.codeBarre = :codeBarre')
			->setParameter('codeBarre', $data['codeBarre']);
		}
	
		// Si la recherche porte sur le nom	
		if($data['nom'] != '')
		{
			$query->andWhere('a.nom = :nom')
			->setParameter('nom', $data['nom']);
		}
		
		$query->leftJoin('a.limites', 'limites', 'WITH', ':nbMembres >= limites.nbMembreMin AND :nbMembres <= limites.nbMembreMax ' )
			->addSelect('limites')
			->setParameter('nbMembres', $data['nbMembres'])
			->join('a.categorie', 'categorie')
			->addSelect('categorie');
	
		return $query->getQuery()->getResult();
	
	}
}
?>