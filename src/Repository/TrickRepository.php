<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use InvalidArgumentException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @param int $currentPage
     * @return Trick[] Returns an array of Trick objects
     */
    public function findLatestEdited(int $currentPage = 1)
    {
        if($currentPage <1){
            throw new InvalidArgumentException("Current page can not be lower than one");
        }

        $query = $this->createQueryBuilder('t')
            ->orderBy('t.editedAt', 'DESC')
            ->getQuery();
        $paginator = $this->paginate($query, $currentPage);

        return $paginator;

    }

    public function paginate($dql, $page = 1, $limit = Trick::NUMBER_OF_DISPLAYED_TRICKS)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    public function findBySearchQuery(array $searchTerms)
    {

        $queryBuilder = $this->createQueryBuilder('p');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('p.name LIKE :term_' . $key)
                ->setParameter('term_' . $key, '%' . $term . '%');

        }

        return $queryBuilder
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
