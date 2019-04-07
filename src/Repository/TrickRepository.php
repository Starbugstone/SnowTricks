<?php

namespace App\Repository;

use App\Entity\Trick;
use App\Pagination\PaginateRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
    use PaginateRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @param int $currentPage
     * @param int $categoryId
     * @return Paginator
     */
    public function findLatestEdited(int $currentPage = 1, int $categoryId = 0)
    {
        if($currentPage <1){
            throw new \InvalidArgumentException("Current page can not be lower than one");
        }

        $query = $this->createQueryBuilder('t');

        if($categoryId>0){
            $query->where('t.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        $query->orderBy('t.editedAt', 'DESC')
            ->getQuery();
        $paginator = $this->paginate($query,Trick::NUMBER_OF_DISPLAYED_TRICKS, $currentPage);

        return $paginator;

    }

    /**
     * @param int $currentPage
     * @param int $tagId
     * @return Paginator
     */
    public function findLatestEditedByTag(int $currentPage = 1, int $tagId = 0)
    {
        if($currentPage <1){
            throw new \InvalidArgumentException("Current page can not be lower than one");
        }

        $query = $this->createQueryBuilder('t');

        if($tagId>0){
            $query->leftJoin('t.tags', 'tag')
                ->where('tag = :tagId')
                ->setParameter('tagId', $tagId);
        }

        $query->orderBy('t.editedAt', 'DESC')
            ->getQuery();
        $paginator = $this->paginate($query,Trick::NUMBER_OF_DISPLAYED_TRICKS, $currentPage);

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
