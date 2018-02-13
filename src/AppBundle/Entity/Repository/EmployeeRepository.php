<?php

namespace AppBundle\Entity\Repository;

class EmployeeRepository extends \Doctrine\ORM\EntityRepository
{
    public function createFindAllQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT e
            FROM AppBundle:Employee e
            "
        );
    }
    public function createFindOneByIdQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT e
            FROM AppBundle:Employee e
            WHERE e.id = :id
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
            FROM AppBundle:Employee e
            WHERE e.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
<<<<<<< HEAD
    public function findEmployeeQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:Employeekey k
            WHERE k.employee = :id
            "
        );
        $query->setParameter('employee', $id);
        return $query;
    }
=======
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
}
