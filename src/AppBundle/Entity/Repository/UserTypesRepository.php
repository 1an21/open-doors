<?php
/**
 * Created by PhpStorm.
 * User: Anton
 * Date: 23.01.2018
 * Time: 16:57
 */
namespace AppBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
class UserTypesRepository extends EntityRepository
{
    public function createFindAllQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT u
            FROM AppBundle:UserTypes u
            "
        );
    }
    public function createFindOneByIdQuery($id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT u
            FROM AppBundle:UserTypes u
            WHERE u.id = :id
            "
        );
        $query->setParameter('id', $id);
        return $query;
    }
}