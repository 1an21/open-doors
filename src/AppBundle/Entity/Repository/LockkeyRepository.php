<?php

namespace AppBundle\Entity\Repository;
use Doctrine\DBAL\DriverManager;
class LockkeyRepository extends \Doctrine\ORM\EntityRepository
{

    public function searchQuery($lock)
    {
        return $this->_em->getRepository('AppBundle:Lockkey')->createQueryBuilder('lk')
        ->where('lk.lock=:lock')
        ->setParameter('lock', $lock);
    }

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
            SELECT e.description, k.tag, l.lock_name, l.lock_pass, ee.name, ee.surname
            FROM AppBundle:Employeekey e
            JOIN AppBundle:Lockkey lk WITH e.rkey = lk.key
            JOIN AppBundle:Lock l WITH lk.lock = l.id
            JOIN AppBundle:Key k WITH lk.key = k.id
            JOIN AppBundle:Employee ee WITH e.employee = ee.id
            WHERE lk.lock = :lock
            "
        );
        $query->setParameter('lock', $lock);
        return $query;
    }

    public function findLockKeyQuery($lock, $id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT e.description, k.tag, l.lock_name, l.lock_pass, ee.name, ee.surname
            FROM AppBundle:Employeekey e
            JOIN AppBundle:Lockkey lk WITH e.rkey = lk.key
            JOIN AppBundle:Lock l WITH lk.lock = l.id
            JOIN AppBundle:Key k WITH lk.key = k.id
            JOIN AppBundle:Employee ee WITH e.employee = ee.id
            WHERE lk.lock = :lock
            AND lk.key= :id
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