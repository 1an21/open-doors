<?php

namespace AppBundle\Entity\Repository;
use Doctrine\DBAL\DriverManager;
class LockkeyRepository extends \Doctrine\ORM\EntityRepository
{

    public function findLockQuery($lock)
    {
        $query = $this->_em->createQuery(
            "
            SELECT l
            FROM AppBundle:Lockkey l
<<<<<<< HEAD
            WHERE l.lock = :lock
            "
        );
        $query->setParameter('lock', $lock);
=======
            WHERE l.lock = :id
            "
        );
        $query->setParameter('id', $lock);
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
        return $query;
    }

    public function findLockKeyQuery($lock, $id)
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
<<<<<<< HEAD
            AND l.lock = :lock
=======
            AND l.Lock = :lock
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
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