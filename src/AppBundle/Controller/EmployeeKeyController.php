<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Key;
use AppBundle\Entity\Repository\KeyRepository;
use AppBundle\Form\Type\KeyType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class KeyController
 * @package AppBundle\Controller
 *
 * @RouteResource("Employee")
 */
class EmployeeKeyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual key
     *
     * @param int $employee
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getKeysAction($employee, $id)
    {
        $key = $this->getKeyRepository()->findEmployeeKeyQuery($employee,$id)->getOneOrNullResult();
        if ($key === null) {
            return new Response(sprintf('Dont exist key with id %s for employee %s', $id , $employee));
        }
        return $key;
    }

    /**
     * Gets a collection of keys for employees
     *
     * @return array
     *
     */
    public function cgetKeysAction($id){

        return $this->getKeyRepository()->findEmployeeQuery($id)->getResult();
    }


    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function postKeysAction($employee, Request $request)
    {
        $rkey = $this->getKeyRepository()->findOneBy(array('Employee'=>$employee));

        $form = $this->createForm(KeyType::class, $rkey, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $key = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($key);
        $em->flush();

        $routeOptions = [
            'id' => $key->getId(),

            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @param int     $employee
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function putKeysAction(Request $request, $employee, $id)
    {

        $key = $this->getKeyRepository()->findOneBy(array('id'=>$id, 'Employee'=>$employee));

        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(KeyType::class, $key, [
            'csrf_protection' => false,]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $key->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);
    }


    /**
     * @param Request $request
     * @param int     $id
     * @param int     $employee
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function patchKeysAction(Request $request, $employee, $id)
    {
        /**
         * @var $key key
         */
        $key = $this->getKeyRepository()->findOneBy(array('id'=>$id, 'Employee'=>$employee));
        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(KeyType::class, $key, [
            'csrf_protection' => false,
        ]);
        $form->submit($request->request->all(), false);
        if (!$form->isValid()) {
            return $form;
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $routeOptions = [
            'id' => $key->getId(),
            '_format' => $request->get('_format'),
        ];
        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
    }


    /**
     * @param int $id
     * @param int $employee
     * @return View
     *
     */
    public function deleteKeysAction($employee, $id)
    {
        $key = $this->getKeyRepository()->deleteEmployeeKeyQuery($employee, $id)->getResult();
        if ($key == 0) {
            return new Response(sprintf('This id %s doesnt exist', $id));
        }
        return new Response(sprintf('Deleted employee #%s', $id));
    }

    /**
     * @return keyRepository
     */
    private function getKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.key');
    }
}
