<?php

namespace AppBundle\Entity\Repository;

class KeyRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllKeyQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:Key k
            "
        );
    }
    public function findKeyQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:Key k
            WHERE k.id = :id
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
            FROM AppBundle:Key k
            WHERE k.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }

    public function deleteEmployeeKeyQuery($employee, $id)
    {
        $query = $this->_em->createQuery(
            "
            DELETE 
            FROM AppBundle:Employeekey k
            WHERE k.rkey = :id
            AND k.employee = :employee

            FROM AppBundle:Key k
            WHERE k.id = :id
            AND k.Employee = :employee

            "
        );
        $query->setParameter('employee', $employee);
        $query->setParameter('id', $id);
        return $query;
    }


    public function findEmployeeQuery($employee)

    public function findEmployeeQuery($id)

    {
        $query = $this->_em->createQuery(
            "
            SELECT k

            FROM AppBundle:Employeekey k
            WHERE k.employee = :employee
            "
        );
        $query->setParameter('employee', $employee);
        return $query;
    }

    public function findEmployeeKeyQuery($employee, $rkey)
            "
            SELECT k
            FROM AppBundle:Key k
            WHERE k.Employee = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }

    public function findEmployeeKeyQuery($employee, $id)

    {
        $query = $this->_em->createQuery(
            "
            SELECT k

            FROM AppBundle:Employeekey k
            WHERE k.employee = :employee
            AND k.rkey= :rkey
            "
        );
        $query->setParameter('employee', $employee);
        $query->setParameter('rkey', $rkey);
        return $query;
    }


}
