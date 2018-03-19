<?php

namespace AppBundle\Entity\Repository;
use Doctrine\DBAL\DriverManager;
class LockkeyRepository extends \Doctrine\ORM\EntityRepository
{

    public function findIdQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:Lockkey k
            WHERE k.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }

    public function findLockQuery($lock)
    {
        $query = $this->_em->createQuery(
            "
            SELECT l as lockkey
            FROM AppBundle:Lockkey l
            WHERE l.lock = :lock
            "
        );
        $query->setParameter('lock', $lock);
        return $query;
    }

    public function findLockKeyQuery($lock, $id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT l as lockkey
            FROM AppBundle:Lockkey l
            WHERE l.lock = :lock
            AND l.key= :id
            "
        );
        $query->setParameter('lock', $lock);
        $query->setParameter('id', $id);
        return $query;
    }

    // public function findLockKeyQuery($lock, $id)
    // {
    //     $query = $this->_em->createQuery(
    //         "
    //         SELECT e, ll
    //         FROM AppBundle:Employeekey e
    //         LEFT JOIN AppBundle:Lockkey l WITH e.id = l.key
    //         LEFT JOIN AppBundle:Lock ll WITH l.lock = ll.id
    //         WHERE l.lock = :lock
    //         AND l.key= :id
    //         "
    //     );
    //     $query->setParameter('lock', $lock);
    //     $query->setParameter('id', $id);
    //     return $query;
    // }

    public function findOnlyLockKeyQuery($lock, $id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT l
            FROM AppBundle:Lockkey l
            WHERE l.lock = :lock
            AND l.key= :id
            "
        );
        $query->setParameter('lock', $lock);
        $query->setParameter('id', $id);
        return $query;
    }
    public function deleteLockKeyQuery($lock, $id)
    {
        $query = $this->_em->createQuery(
            "
            DELETE 
            FROM AppBundle:Lockkey l
            WHERE l.key = :id
            AND l.lock = :lock
            "
        );
        $query->setParameter('lock', $lock);
        $query->setParameter('id', $id);
        return $query;
    }

    public function insertQuery($id)
    {

    }
}