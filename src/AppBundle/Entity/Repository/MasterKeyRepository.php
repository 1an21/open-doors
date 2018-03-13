<?php

namespace AppBundle\Entity\Repository;

class MasterKeyRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllKeyQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:MasterKey k
            "
        );
    }
    public function findKeyQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:MasterKey k
            WHERE k.tag = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
    public function deleteKeyQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            DELETE 
            FROM AppBundle:MasterKey k
            WHERE k.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }

    
}
