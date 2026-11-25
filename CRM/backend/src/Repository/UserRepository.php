<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[]
     */
    public function search(array $filters): array
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC');

        if (!empty($filters['name'])) {
            $qb->andWhere('u.name LIKE :name')
                ->setParameter('name', '%'.$filters['name'].'%');
        }

        if (!empty($filters['email'])) {
            $qb->andWhere('u.email LIKE :email')
                ->setParameter('email', '%'.$filters['email'].'%');
        }

        $day = DateFilter::parse($filters['date'] ?? null);
        if ($day) {
            $qb->andWhere('u.lastActivity >= :from AND u.lastActivity < :to')
                ->setParameter('from', $day)
                ->setParameter('to', $day->modify('+1 day'));
        }

        return $qb->getQuery()->getResult();
    }
}
