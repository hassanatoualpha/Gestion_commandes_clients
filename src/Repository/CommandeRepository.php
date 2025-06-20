<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function save(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Commande[] Returns an array of Commande objects for a specific client
     */
    public function findByClient(Client $client): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.client = :client')
            ->setParameter('client', $client)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Commande[] Returns an array of pending orders
     */
    public function findPendingOrders(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.statut = :statut')
            ->setParameter('statut', false)
            ->orderBy('c.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}