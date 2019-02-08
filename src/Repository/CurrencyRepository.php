<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CurrencyRepository extends EntityRepository
{
    protected function getQueryBuilder()
    {
        $qb = $this->createQueryBuilder('currency');

        return $qb;
    }

    public function getAllBySource(string $source)
    {
        $qb = $this->getQueryBuilder()
            ->where('LOWER(currency.source) = LOWER(:source)')
            ->setParameter('source', $source)
        ;

        return $qb->getQuery()->getResult();
    }

    public function getOneByCode(string $code, string $source)
    {
        $qb = $this->getQueryBuilder()
            ->where('currency.active = true')
            ->andWhere('LOWER(currency.code) = LOWER(:code)')
            ->andWhere('LOWER(currency.source) = LOWER(:source)')
            ->setParameter('source', $source)
            ->setParameter('code', $code)
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}