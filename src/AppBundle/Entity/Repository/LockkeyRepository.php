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
            SELECT l as lockkey, em as employee
            FROM AppBundle:Lockkey l, AppBundle:Employee em
            LEFT JOIN AppBundle:Employeekey e WITH l.key = e.rkey
            LEFT JOIN em WITH e.employee = em.id
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
            SELECT l as lockkey, em as employee
            FROM AppBundle:Lockkey l
            LEFT JOIN AppBundle:Employeekey e WITH l.key = e.rkey
            LEFT JOIN AppBundle:Employee em WITH e.employee = em.id
            WHERE l.lock = :lock
            AND l.key= :id
            "
        );
        $query->setParameter('lock', $lock);
        $query->setParameter('id', $id);
        return $query;
    }

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