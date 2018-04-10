<?php

namespace AppBundle\Entity\Repository;

class LogRepository extends \Doctrine\ORM\EntityRepository
{
    

    
public function FindLockKeyQuery()
    {
        // $dates = new \DateTime();
        // $dates->modify('-5 minute');
        $query =  $this->_em->createQuery(
            "
            SELECT l.time, l.msg, l.lockName,  l.tag
            FROM AppBundle:LogLockKey l
            
            "
            
        );
        //$query->setParameter('dates', $dates);
        return $query;
    }
    public function FindMasterKeyQuery()
    {
        
        $query =  $this->_em->createQuery(
            "
            SELECT m.time, m.msg, m.tagMk as tag, m.oldTag as oldtag
            FROM AppBundle:LogMasterKey m
            "

        );

        return $query;
    }


}
