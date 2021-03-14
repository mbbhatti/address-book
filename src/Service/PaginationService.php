<?php

namespace App\Service;

use App\Repository\AddressBookRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    /**
     * @var AddressBookRepository
     */
    private $addressBookRepository;

    /**
     * @var int
     */
    private $limit;

    /**
     * PaginationService constructor.
     * @param int $limit
     * @param AddressBookRepository $addressBookRepository
     */
    public function __construct(int $limit, AddressBookRepository $addressBookRepository)
    {
        $this->addressBookRepository = $addressBookRepository;
        $this->limit = $limit;
    }

    /**
     * @param Request $request
     * @return Paginator
     */
    public function paginate(Request $request): Paginator
    {
        $currentPage = $request->query->getInt('p') ?: 1;
        $query = $this->addressBookRepository->getQuery();
        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($this->limit * ($currentPage - 1))
            ->setMaxResults($this->limit);

        return $paginator;
    }

    /**
     * @param Paginator $paginator
     * @return int
     */
    public function lastPage(Paginator $paginator): int
    {
        return ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
    }
}

