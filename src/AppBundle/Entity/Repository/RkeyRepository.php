<?php

namespace AppBundle\Entity\Repository;

class RkeyRepository extends \Doctrine\ORM\EntityRepository
{
    public function createFindAllQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT r
            FROM AppBundle:Rkey r
            "
        );
    }
    public function createFindOneByIdQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT r
            FROM AppBundle:Rkey r
            WHERE r.id = :id
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
            FROM AppBundle:Rkey r
            WHERE r.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
}
