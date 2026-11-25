<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @return Company[]
     */
    public function search(array $filters): array
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC');

        if (!empty($filters['name'])) {
            $qb->andWhere('c.name LIKE :name')
                ->setParameter('name', '%'.$filters['name'].'%');
        }

        if (!empty($filters['email'])) {
            $qb->andWhere('c.email LIKE :email')
                ->setParameter('email', '%'.$filters['email'].'%');
        }

        $day = DateFilter::parse($filters['date'] ?? null);
        if ($day) {
            $qb->andWhere('c.lastActivity >= :from AND c.lastActivity < :to')
                ->setParameter('from', $day)
                ->setParameter('to', $day->modify('+1 day'));
        }

        return $qb->getQuery()->getResult();
    }
}
