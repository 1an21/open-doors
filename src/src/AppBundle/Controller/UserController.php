<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\UserRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package AppBundle\Controller
 *
 * @RouteResource("User")
 */
class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual User
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getAction($id)
    {
        $user = $this->getUserRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();

        if ($user === null) {
            return new Response(sprintf('Dont exist user with id %s', $id));
        }
        return $user;
    }

    /**
     * Gets a collection of users
     *
     * @return array
     */
    public function cgetAction()
    {
        return $this->getUserRepository()->createFindAllQuery()->getResult();
    }

    /**
     * @param int $id
     * @return Response
     */
    public function deleteAction($id)
    {
        $user = $this->getUserRepository()->deleteQuery($id)->getResult();
        if ($user == 0) {
            return new Response(sprintf('This id %s doesnt exist', $id));
        }
        return new Response(sprintf('Deleted user #%s', $id));
    }

    /**
     * @return UserRepository
     */
    private function getUserRepository()
    {
        return $this->get('crv.doctrine_entity_repository.user');
    }
}
