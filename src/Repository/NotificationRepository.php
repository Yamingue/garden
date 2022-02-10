<?php

namespace App\Repository;

use App\Entity\Ecole;
use App\Entity\Enfant;
use App\Entity\User;
use App\Entity\Notification;
use App\Entity\Salle;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    // /**
    //  * @return Notification[] Returns an array of Notification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findParentToday(User $user, Enfant $e): ?Notification
    {
        $date = new DateTime();
        $time = $date->format("Y-m-d");
        $query =  $this->createQueryBuilder('n')
            ->join('n.enfant', 'e')
            ->where('e = n.enfant')
            ->andWhere('e = :enfant')
            ->andWhere('n.create_at like :date')
            ->andWhere('n.parent = :p')
            ->setParameter('date', "%$time%")
            ->setParameter('enfant', $e)
            ->setParameter('p', $user)
        ;
        return $query->getQuery()->getOneOrNullResult();
    }

    public function findSalleOnWay(Salle $salle)
    {
        $date = new DateTime();
        $time = $date->format("Y-m-d");
        return $this->createQueryBuilder('n')
            ->where("n.close = false")
            ->andWhere("n.waiting = false")
            ->andWhere('n.create_at like :date')
            ->andWhere('n.salle = :salle')
            ->setParameter('salle', $salle)
            ->setParameter('date', "%$time%")
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findEcoleOnWay(Ecole $ecole): ?array
    {
        $date = new DateTime();
        $time = $date->format("Y-m-d");
        return $this->createQueryBuilder('n')
            ->where("n.close = false")
            ->andWhere("n.waiting = false")
            ->andWhere('n.create_at like :date')
            ->andWhere('n.ecole = :ecole')
            ->setParameter('ecole', $ecole)
            ->setParameter('date', "%$time%")
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSalleParking(Salle $salle)
    {
        $date = new DateTime();
        $time = $date->format("Y-m-d");
        return $this->createQueryBuilder('n')
            ->where("n.close = false")
            ->andWhere("n.waiting = true ")
            ->andWhere('n.create_at like :date')
            ->andWhere('n.salle = :salle')
            ->setParameter('salle', $salle)
            ->setParameter('date', "%$time%")
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findSuperParking(Ecole $ecole)
    {
        $date = new DateTime();
        $time = $date->format("Y-m-d");
        return $this->createQueryBuilder('n')
            ->where("n.close = false")
            ->andWhere("n.waiting = true ")
            ->andWhere('n.create_at like :date')
            ->andWhere('n.ecole = :ecole')
            ->setParameter('ecole', $ecole)
            ->setParameter('date', "%$time%")
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
