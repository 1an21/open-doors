<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Employeekey;
use AppBundle\Entity\Repository\KeyRepository;
use AppBundle\Entity\Repository\EmployeekeyRepository;
use AppBundle\Form\Type\KeyType;
use AppBundle\Form\Type\EmployeeKeyType;
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
     * @param int $rkey
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getKeysAction($employee, $rkey)
    {
        $key = $this->getEmployeekeyRepository()->findEmployeeKeyQuery($employee,$rkey)->getOneOrNullResult();
        if ($key === null) {
            return new Response(sprintf('Dont exist key with id %s for employee %s', $rkey , $employee));
        }
        return $key;
    }

    /**
     * Gets a collection of keys for employees
     *
     * @return array
     *
     */
    public function cgetKeysAction($employee){

        return $this->getEmployeekeyRepository()->findEmployeeQuery($employee)->getResult();
    }


    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function postKeysAction(Request $request, $employee)
    {
        $this->getEmployeeRepository()->createFindOneByIdQuery($employee)->getOneOrNullResult();
        $em = $this->get('doctrine')->getManager();
        $emp=$em->getRepository('AppBundle:Employee')->findOneById($employee);
        $em->flush();

        $employeekey=new Employeekey();
        $employeekey->setEmployee($emp);

        $form = $this->createForm(EmployeeKeyType::class, $employeekey, [
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

        $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
        $id=$employeekey->getId();
        return $this->getEmployeekeyRepository()->findIdQuery($id)->getOneOrNullResult();
    }

    /**
     * @param Request $request
     * @param int     $id
     * @param int     $employee
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function putKeysAction(Request $request, $employee, $rkey)
    {

        $key = $this->getEmployeeKeyRepository()->findOneBy(array('rkey'=>$rkey, 'employee'=>$employee));

        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(EmployeeKeyType::class, $key, [
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

        $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);
        $id=$key->getId();
        return $this->getEmployeekeyRepository()->findIdQuery($id)->getOneOrNullResult();
    }


    /**
     * @param Request $request
     * @param int     $rkey
     * @param int     $employee
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function patchKeysAction(Request $request, $employee, $rkey)
    {


        $key = $this->getEmployeeKeyRepository()->findOneBy(array('rkey'=>$rkey, 'employee'=>$employee));

        if ($key === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(EmployeeKeyType::class, $key, [
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
        $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
        $id=$key->getId();
        return $this->getEmployeekeyRepository()->findIdQuery($id)->getOneOrNullResult();
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
            return new View("This id $id doesnt exist");
        }
        return new View("Deleted relationship $id");
    }

    /**
     * @return keyRepository
     */
    private function getKeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.key');
    }
    private function getEmployeeRepository()
    {
        return $this->get('crv.doctrine_entity_repository.employee');
    }
    private function getEmployeekeyRepository()
    {
        return $this->get('crv.doctrine_entity_repository.employeekey');
    }
}
