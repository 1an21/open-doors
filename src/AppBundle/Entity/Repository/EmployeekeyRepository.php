<?php

namespace AppBundle\Entity\Repository;

class EmployeekeyRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT k
            FROM AppBundle:Employeekey k
            "
        );
    }
    public function findEmployeeQuery($employee)
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
    public function deleteEmployeeKeyQuery($employee, $id)
    {
        $query = $this->_em->createQuery(
            "
            DELETE 
            FROM AppBundle:Employeekey k
            WHERE k.rkey = :id
            AND k.employee = :employee
            "
        );
        $query->setParameter('employee', $employee);
        $query->setParameter('id', $id);
        return $query;
    }


    public function findEmployeeKeyQuery($employee, $rkey)
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
