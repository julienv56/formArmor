<?php

namespace FormArmorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * InscriptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InscriptionRepository extends EntityRepository
{
    
    public function listeInscriptions($page, $nbParPage, $idclient)
    {
        $queryBuilder = $this->createQueryBuilder('i')
                ->andWhere('i.client = :idclient')
                ->setParameter('idclient', $idclient);
        
        
        $query = $queryBuilder->getQuery();
        $query
        ->setFirstResult(($page-1)* $nbParPage)
        ->setMaxResults($nbParPage);
        return new Paginator($query, true);
                
    }

    public function getInscriptions($idSession)
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->andWhere('i.session_formation = :idSession')
            ->setParameter('idSession', $idSession)
            ->orderBy('i.id', 'ASC');

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
