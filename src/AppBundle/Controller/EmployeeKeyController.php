<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Key;
use AppBundle\Entity\Repository\KeyRepository;
<<<<<<< HEAD
use AppBundle\Entity\Repository\EmployeekeyRepository;
use AppBundle\Form\Type\KeyType;
use AppBundle\Form\Type\EKeyType;
=======
use AppBundle\Form\Type\KeyType;
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
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
<<<<<<< HEAD
    public function cgetKeysAction($employee){

        return $this->getKeyRepository()->findEmployeeQuery($employee)->getResult();
=======
    public function cgetKeysAction($id){

        return $this->getKeyRepository()->findEmployeeQuery($id)->getResult();
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
    }


    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     */
<<<<<<< HEAD
    public function postKeysAction(Request $request, $employee)
    {
        $rkey = $this->getEmployeeRepository()->find($employee);
        print_r($rkey);
        die();
        
        if ($rkey === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(EKeyType::class, $rkey, [
=======
    public function postKeysAction($employee, Request $request)
    {
        $rkey = $this->getKeyRepository()->findOneBy(array('Employee'=>$employee));

        $form = $this->createForm(KeyType::class, $rkey, [
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());
<<<<<<< HEAD
=======

>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
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
<<<<<<< HEAD
     * @param int     $rkey
=======
     * @param int     $id
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
     * @param int     $employee
     * @return View|\Symfony\Component\Form\Form
     *
     */
<<<<<<< HEAD
    public function patchKeysAction(Request $request, $employee, $rkey)
    {
        
        //$key = $this->getEmployeeRepository()->find($employee);
        $kkey = $this->getEmployeekeyRepository()->findAllQuery()->getResult();
        print_r( $kkey);
        die();
=======
    public function patchKeysAction(Request $request, $employee, $id)
    {
        /**
         * @var $key key
         */
        $key = $this->getKeyRepository()->findOneBy(array('id'=>$id, 'Employee'=>$employee));
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
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
<<<<<<< HEAD
        return new Response(sprintf('Deleted relationship #%s', $id));
=======
        return new Response(sprintf('Deleted employee #%s', $id));
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
    }

    /**
     * @return keyRepository
     */
    private function getKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.key');
    }
<<<<<<< HEAD
    private function getEmployeeRepository()
    {
        return $this->get('crv.doctrine_entity_repository.employee');
    }
    private function getEmployeekeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.employeekey');
    }
=======
>>>>>>> 3bc210f76dea6c544859efa28b8f049cd025314d
}
