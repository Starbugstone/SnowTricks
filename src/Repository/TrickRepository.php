<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
     * @return Trick[] Returns an array of Trick objects
     */
    public function findLatestEdited($limit = Trick::NUMBER_OF_DISPLAYED_TRICKS)
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.editedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    /**
     * @param string $query
     * @return array
     */
    public function findBySearchQuery(string $query): array
    {
        if($query === ""){
            return $this->findAll();
        }
        $query = $this->sanitizeSearchQuery($query);
        $searchTerms = $this->extractSearchTerms($query);

        if (\count($searchTerms) === 0) {
            return [];
        }

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

    /**
     * @param string $query
     * @return string
     * Removes all non alphanum characters except whitespace
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return trim(preg_replace('/[[:space:]]+/', ' ', $query));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', $searchQuery));
        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }
}
