<?php

namespace App\Repository;

use App\Entity\Composant;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Composant>
 *
 * @method Composant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Composant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Composant[]    findAll()
 * @method Composant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComposantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Composant::class);
    }

    /**
     * Recherche les composants en fonction du formulaire
     * @return void
     */
    public function search(string $mots = NULL, int $offset = 0, int $limit = NULL)
    {
        $query = $this->createQueryBuilder('c');
        if (isset($mots)) {
            $query
                ->where('MATCH_AGAINST(c.intitule, c.textContent) AGAINST (:mots boolean)>0')
                ->setParameter(':mots', $mots)
            ;
        }
        $query
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
            
        return $query->getQuery()->getResult();
    }

    public function add(Composant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Composant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Composant[] Returns an array of Composant objects
//     */
//    public function findByType($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.type = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Composant
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * R??cup??re les composants cibl??s par la recherche
     * @return Composant[]
     */
    public function findSearch()
    {
        return $this->findAll();
    }
}
