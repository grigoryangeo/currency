<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CurrencyRepository extends EntityRepository
{
    public function getAllBySource(string $source)
    {
        $qb = $this->getActiveQueryBuilder()
            ->where('LOWER(currency.source) = LOWER(:source)')
            ->setParameter('source', $source)
        ;

        return $qb->getQuery()->getResult();
    }

    protected function getActiveQueryBuilder()
    {
        $qb = $this->createQueryBuilder('currency');

        return $qb;
    }
}