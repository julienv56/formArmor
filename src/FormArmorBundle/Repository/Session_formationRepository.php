<?php

namespace FormArmorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Session_formationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class Session_formationRepository extends EntityRepository
{
    public function listeSessionsAdmin1($page, $nbParPage) // Liste toutes les sessions avec pagination
    {
        $DateToday = new \DateTime('now');
        $DateToday->modify('-1 day')->format('Y-m-d');
        $Date2 = new \DateTime('now');
        $Date2->modify('+8 day')->format('Y-m-d');

        $queryBuilder = $this->createQueryBuilder('s')
            ->andWhere('s.dateDebut BETWEEN :today AND :Date2')
            ->andWhere('s.nbInscrits = s.nbPlaces')
            ->setParameter('today', $DateToday)
            ->setParameter('Date2', $Date2)
            ->orderBy('s.id', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->setFirstResult(($page - 1) * $nbParPage)
            ->setMaxResults($nbParPage);
        return new Paginator($query, true);
    }

    public function listeSessionsAdmin2($page, $nbParPage) // Liste toutes les sessions avec pagination
    {
        $DateToday = new \DateTime('now');
        $DateToday->modify('+8 day')->format('Y-m-d');
        $Date2 = new \DateTime('now');
        $Date2->modify('+2 month')->format('Y-m-d');

        $queryBuilder = $this->createQueryBuilder('s')
            ->andWhere('s.dateDebut BETWEEN :today AND :Date2')
            ->andWhere('s.nbInscrits < s.nbPlaces')
            ->setParameter('today', $DateToday)
            ->setParameter('Date2', $Date2)
            ->orderBy('s.id', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->setFirstResult(($page - 1) * $nbParPage)
            ->setMaxResults($nbParPage);
        return new Paginator($query, true);
    }

	public function listeSessions($page, $nbParPage) // Liste toutes les sessions avec pagination
	{
        $DateToday = new \DateTime('now');
        $Date2 = new \DateTime('now');
        $Date2->modify('+2 month')->format('Y-m-d');
            
		$queryBuilder = $this->createQueryBuilder('s')
                        ->andWhere('s.dateDebut BETWEEN :today AND :Date2')
                        ->setParameter('today', $DateToday)
                        ->setParameter('Date2', $Date2)
                        ->orderBy('s.id', 'ASC');

		// On récupère la Query à partir du QueryBuilder
		$query = $queryBuilder->getQuery();

		// On gère ensuite la pagination grace au service Paginator qui nous fournit
		// entre autres les méthodes setFirstResult et setMaxResults
		$query
		  // On définit la formation à partir de laquelle commencer la liste
		  ->setFirstResult(($page-1) * $nbParPage)
		  // Ainsi que le nombre de formations à afficher sur une page
		  ->setMaxResults($nbParPage)
		;

		// Enfin, on retourne l'objet Paginator correspondant à la requête construite
		// (=>Ne pas oublier le "use Doctrine\ORM\Tools\Pagination\Paginator;" correspondant en début de fichier)
		return new Paginator($query, true);
	}
        
	public function suppSession($id) // Suppression de la session d'identifiant $id
	{
		$qb = $this->createQueryBuilder('s');
        $qb->delete('FormArmorBundle\Entity\Session_formation', 's')
            ->where('s.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();
	}

    public function getSession($idSession)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s');
        $qb->andWhere('s.id = :id')->setParameter('id', $idSession);

        return $qb->getQuery()->getResult();
    }
}