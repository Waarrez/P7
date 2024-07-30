<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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
     * @param User $entity
     * @param bool $flush
     * @return void
     */
    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param User $entity
     * @param bool $flush
     * @return void
     */
    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Customer $customer
     * @return float|int|mixed|string
     */
    public function findUsersByCustomer(Customer $customer): mixed
    {
        return $this->createQueryBuilder('u')
            ->where('u.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function findUserWithIdAndCustomer(string $id, Customer $customer, bool $throwException = false): ?User
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->andWhere('u.customer = :customer')
            ->setParameter('id', $id)
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getOneOrNullResult();

        if ($throwException && !$user) {
            throw new \Exception('User not found');
        }

        return $user;
    }
}
