<?php

namespace AppBundle\Entity\Repository;

/**
 * SensorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LockRepository extends \Doctrine\ORM\EntityRepository
{
    public function searchQuery()
    {
        return $this->_em->getRepository('AppBundle:Lock')->createQueryBuilder('l');
    }

    public function createFindAllQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT l
            FROM AppBundle:Lock l
            "
        );
    }
    public function createFindOneByIdQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT l
            FROM AppBundle:Lock l
            WHERE l.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
    public function deleteQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            DELETE 
            FROM AppBundle:Lock l
            WHERE l.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
}
