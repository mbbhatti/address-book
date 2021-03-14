<?php

namespace App\Repository;

use App\Entity\AddressBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query;

class AddressBookRepository extends ServiceEntityRepository
{
    /**
     * AddressBookRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddressBook::class);
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->createQueryBuilder('a')->getQuery();

    }

    /**
     * @param int $id
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteById(int $id)
    {
        $address = $this->find($id);
        if ($address === null) {
            return false;
        }
        $file = $address->getPicture();
        $this->_em->remove($address);
        $this->_em->flush();

        return $file;
    }
}

