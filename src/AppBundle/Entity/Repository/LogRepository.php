<?php

namespace AppBundle\Entity\Repository;

class LogRepository extends \Doctrine\ORM\EntityRepository
{
    

    
public function createFindLockKeyQuery()
    {
        // $dates = new \DateTime();
        // $dates->modify('-5 minute');
        $query =  $this->_em->createQuery(
            "
            SELECT e.action, e.loggedAt, l.lock_name, l.lock_pass, k.tag
            FROM AppBundle:ExtLogEntries e,  AppBundle:Lockkey lk
            JOIN AppBundle:Lock l with lk.lock=l.id
            JOIN AppBundle:Key k with lk.key=k.id
            WHERE e.objectId=lk.id
            AND e.objectClass='AppBundle\Entity\Lockkey'
            -- AND e.loggedAt <= CURRENT_TIMESTAMP()
            -- AND e.loggedAt >= :dates
            "
            
        );
        //$query->setParameter('dates', $dates);
        return $query;
    }
    public function createFindMasterKeyQuery()
    {
        $dates = new \DateTime();
        $dates->modify('-35 minute');
        $query =  $this->_em->createQuery(
            "
            SELECT e.action, e.loggedAt, mk.tag
            FROM AppBundle:ExtLogEntries e,  AppBundle:MasterKey mk
            WHERE e.objectId=mk.id
            AND e.objectClass='AppBundle\Entity\MasterKey'
            AND e.loggedAt <= CURRENT_TIMESTAMP()
            AND e.loggedAt >= :dates
            "

        );
        $query->setParameter('dates', $dates);
        return $query;
    }


}
