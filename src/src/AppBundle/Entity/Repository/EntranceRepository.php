<?php

namespace AppBundle\Entity\Repository;
use Doctrine\DBAL\DriverManager;
class EntranceRepository extends \Doctrine\ORM\EntityRepository
{

    public function searchQuery()
    {
        return $this->_em->getRepository('AppBundle:Entrance')->createQueryBuilder('en')
            ->select("en.time","en.result", "l.lock_name","k.tag", "e.name", "e.surname", "e.age")
            ->join("AppBundle\Entity\Key", "k", "WITH", "k.id=en.key")
            ->join("AppBundle\Entity\Lock", "l", "WITH", "l.id=en.lock")
            ->join("AppBundle\Entity\Employeekey", "ek", "WITH","ek.rkey=k.id")
            ->join("AppBundle\Entity\Employee", "e", "WITH","e.id=ek.employee")
            ->addOrderBy('en.id', 'DESC');
    }

    public function findIdQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:Entrance k
            WHERE k.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }

    public function findLockQuery()
    {
        $query = $this->_em->createQuery(
            "
            SELECT e
            FROM AppBundle:Entrance e
            
            "
        );
        
        return $query;
    }

    public function findEntranceQuery($lock, $id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT l.id as lock_id, k.id as key_id, e.description, k.tag, l.lock_name, l.lock_pass, ee.name, ee.surname
            FROM AppBundle:Employeekey e
            JOIN AppBundle:Entrance lk WITH e.rkey = lk.key
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

    // public function findEntranceQuery($lock, $id)
    // {
    //     $query = $this->_em->createQuery(
    //         "
    //         SELECT e, ll
    //         FROM AppBundle:Employeekey e
    //         LEFT JOIN AppBundle:Entrance l WITH e.id = l.key
    //         LEFT JOIN AppBundle:Lock ll WITH l.lock = ll.id
    //         WHERE l.lock = :lock
    //         AND l.key= :id
    //         "
    //     );
    //     $query->setParameter('lock', $lock);
    //     $query->setParameter('id', $id);
    //     return $query;
    // }

    public function findOnlyEntranceQuery($lock, $id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT l
            FROM AppBundle:Entrance l
            WHERE l.lock = :lock
            AND l.key= :id
            "
        );
        $query->setParameter('lock', $lock);
        $query->setParameter('id', $id);
        return $query;
    }
    public function deleteEntranceQuery($lock, $id)
    {
        $query = $this->_em->createQuery(
            "
            DELETE 
            FROM AppBundle:Entrance l
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