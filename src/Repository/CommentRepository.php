<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Pagination\PaginateRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    use PaginateRepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findLatestEdited(int $trickId, int $currentPage = 1)
    {
        if ($currentPage < 1) {
            throw new InvalidArgumentException("Current page can not be lower than one");
        }

        $query = $this->createQueryBuilder('c')
            ->where('c.trick = :trickId')
            ->setParameter('trickId', $trickId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();

        $paginator = $this->paginate($query,Comment::NUMBER_OF_DISPLAYED_COMMENTS, $currentPage);

        return $paginator;
    }
}
