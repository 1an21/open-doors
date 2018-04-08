<?php

namespace AppBundle\Entity\Repository;

class KeyRepository extends \Doctrine\ORM\EntityRepository
{
    public function searchQuery()
    {
        return $this->_em->getRepository('AppBundle:Key')->createQueryBuilder('k');
    }

    public function findAllKeyQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT k.id, k.tag, ek.description, e.name, e.surname
            FROM AppBundle:Key k
            JOIN AppBundle:Employeekey ek with ek.rkey=k.id
            JOIN AppBundle:Employee e with ek.employee=e.id
            "
        );
    }

    public function findFreeKeysQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT k.id, k.tag
            FROM AppBundle:Key k
            JOIN AppBundle:Employeekey ek with ek.rkey=k.id and ek.id IS NULL
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
            "
        );
        $query->setParameter('employee', $employee);
        $query->setParameter('id', $id);
        return $query;
    }

    public function findEmployeeQuery($employee)
    {
        $query = $this->_em->createQuery(
            "
            SELECT e
            FROM AppBundle:Employeekey e
            WHERE e.employee = :employee
            "
        );
        $query->setParameter('employee', $employee);
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
        $query->setParameter('id', $rkey);
        return $query;
    }



    public function insertQuery($id)
    {

    }
}
