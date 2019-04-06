<?php

namespace App\Pagination;


use Doctrine\ORM\Tools\Pagination\Paginator;

trait PaginateRepositoryTrait{

    public function paginate($dql, int $limit, int $page = 1)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))// Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}