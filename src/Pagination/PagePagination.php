<?php

namespace App\Pagination;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PagePagination
{

    /**
     * Take a paginator list, the actual page and the number of displayed elements
     * checks and returns the next page or 0 if we are at the end
     * @param Paginator $list
     * @param int $page
     * @param int $displayedNumber
     * @return int
     */
    public function nextPage(Paginator $list, int $page, int $displayedNumber)
    {
        $totalList = $list->count();

        if ($totalList > 0 && $page > ceil($totalList / $displayedNumber)) {
            throw new NotFoundHttpException("Page does not exist");
        }

        $nextPage = 0;
        if (!($page + 1 > ceil($totalList / $displayedNumber))) {
            $nextPage = $page + 1;
        }

        return $nextPage;
    }

}