<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @return Client[]
     */
    public function search(array $filters): array
    {
        $qb = $this->createQueryBuilder('cl')
            ->leftJoin('cl.company', 'co')
            ->addSelect('co')
            ->orderBy('cl.id', 'DESC');

        if (!empty($filters['name'])) {
            $qb->andWhere('cl.name LIKE :name')
                ->setParameter('name', '%'.$filters['name'].'%');
        }

        if (!empty($filters['email'])) {
            $qb->andWhere('cl.email LIKE :email')
                ->setParameter('email', '%'.$filters['email'].'%');
        }

        // yuqoridagi "Kompaniya: ..." select
        if (!empty($filters['companyId'])) {
            $qb->andWhere('co.id = :companyId')
                ->setParameter('companyId', (int) $filters['companyId']);
        }

        // "Ishlash joyi qidirish" inputi
        if (!empty($filters['company'])) {
            $qb->andWhere('co.name LIKE :company')
                ->setParameter('company', '%'.$filters['company'].'%');
        }

        $day = DateFilter::parse($filters['date'] ?? null);
        if ($day) {
            $qb->andWhere('cl.lastActivity >= :from AND cl.lastActivity < :to')
                ->setParameter('from', $day)
                ->setParameter('to', $day->modify('+1 day'));
        }

        return $qb->getQuery()->getResult();
    }
}
