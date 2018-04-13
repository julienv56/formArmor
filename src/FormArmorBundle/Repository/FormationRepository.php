<?php

namespace FormArmorBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * FormationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormationRepository extends EntityRepository
{
	public function listeFormations($page, $nbParPage)
	{
		// Méthode 1 : en passant par l'EntityManager
		$queryBuilder = $this->_em->createQueryBuilder()
		  ->select('f')
		  ->from($this->_entityName, 'f')
		;
		// Dans un repository, $this->_entityName est le namespace de l'entité gérée
		// Ici, il vaut donc OC\FormArmorBundle\Entity\Formation

		// Méthode 2 : en passant par le raccourci (c'est préférable)
		$queryBuilder = $this->createQueryBuilder('f');

		// On n'ajoute pas de critère ou tri particulier ici car on veut toutes les formations, la construction
		// de notre requête est donc finie

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
	public function suppFormation($id) // Suppression de la formation d'identifiant $id
	{
		$qb = $this->createQueryBuilder('f');
		$query = $qb->delete('FormArmorBundle\Entity\Formation', 'f')
		  ->where('f.id = :id')
		  ->setParameter('id', $id);
		
		return $qb->getQuery()->getResult();
	}
        
        public function getFormation($idFormation)
        {
             $qb = $this->createQueryBuilder('f');
            $qb->select('f');
            $qb->andWhere('f.id = :id')->setParameter('id', $idFormation);

            return $qb->getQuery()->getResult();
        }
}
