<?php

namespace AppBundle\Entity\Repository;

class LogRepository extends \Doctrine\ORM\EntityRepository
{
    

    
public function FindLockKeyQuery($id)
    {
        $query =  $this->_em->createQuery(
            "
            SELECT l.id, l.time, l.msg, l.lockName,  l.tag
            FROM AppBundle:LogLockKey l
            WHERE l.id > :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
    public function FindMasterKeyQuery($id)
    {
        
        $query =  $this->_em->createQuery(
            "
            SELECT m.id, m.time, m.msg, m.tagMk as tag, m.oldTag as oldtag
            FROM AppBundle:LogMasterKey m
            WHERE m.id > :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }


}
