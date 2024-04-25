<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findCurrentEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.startDate <= :currDate')
            ->andWhere('e.endDate >= :currDate')
            ->andWhere('e.enabled = true')
            ->setParameter('currDate', (new \DateTime())->setTimezone(new \DateTimeZone('Europe/Vilnius')))
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findPreviousEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.endDate < :currDate')
            ->andWhere('e.enabled = true')
            ->setParameter('currDate', (new \DateTime())->setTimezone(new \DateTimeZone('Europe/Vilnius')))
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findUpcomingEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.startDate > :currDate')
            ->andWhere('e.enabled = true')
            ->setParameter('currDate', (new \DateTime())->setTimezone(new \DateTimeZone('Europe/Vilnius')))
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
            ;
    }
}
