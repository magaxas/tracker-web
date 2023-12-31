<?php

namespace App\Repository;

use App\Entity\DataPacket;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DataPacket>
 *
 * @method DataPacket|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataPacket|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataPacket[]    findAll()
 * @method DataPacket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataPacketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataPacket::class);
    }

    /**
     * @return DataPacket[] Returns an array of DataPacket objects
     * @throws NonUniqueResultException
     */
    public function getEventDateRange(Event $event): array
    {
        $startDate = $this->createQueryBuilder('dp')
            ->select('dp.date')
            ->andWhere('dp.event = :event')
            ->setParameter('event', $event)
            ->orderBy('dp.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
        $endDate = $this->createQueryBuilder('dp')
            ->select('dp.date')
            ->andWhere('dp.event = :event')
            ->setParameter('event', $event)
            ->orderBy('dp.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        return [
            'startDate' => $startDate[0]['date'],
            'endDate' =>$endDate[0]['date']
        ];
    }

//    public function findOneBySomeField($value): ?DataPacket
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
