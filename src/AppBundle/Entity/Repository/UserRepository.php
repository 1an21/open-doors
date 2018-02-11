<?php

namespace AppBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function createFindAllQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT u
            FROM AppBundle:User u
            "
        );
    }
    public function createFindOneByIdQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT u
            FROM AppBundle:User u
            WHERE u.id = :id
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
            FROM AppBundle:User u
            WHERE u.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
}