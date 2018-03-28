<?php

namespace AppBundle\Entity\Repository;

class LogRepository extends \Doctrine\ORM\EntityRepository
{
    

    public function createFindAllQuery()
    {
        $dates = new \DateTime();
        $dates->modify('-5 minute');
        $query =  $this->_em->createQuery(
            "
            SELECT e
            FROM AppBundle:ExtLogEntries e
            WHERE e.loggedAt <= CURRENT_TIMESTAMP()
            AND e.loggedAt >= :dates
            "
            
        );
        $query->setParameter('dates', $dates);
        return $query;
    }

}
