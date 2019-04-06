<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findLatestEdited(int $trickId, int $currentPage = 1)
    {
        if ($currentPage < 1) {
            throw new \InvalidArgumentException("Current page can not be lower than one");
        }

        $query = $this->createQueryBuilder('c')
            ->where('c.trick = :trickId')
            ->setParameter('trickId', $trickId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }

    public function paginate($dql, $page = 1, $limit = Comment::NUMBER_OF_DISPLAYED_COMMENTS)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))// Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}
