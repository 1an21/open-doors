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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *     output="AppBundle\Entity\EmployeeKey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
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
     * @ApiDoc(
     *     output="AppBundle\Entity\EmployeeKey",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetKeysAction($employee){

        return $this->getEmployeekeyRepository()->findEmployeeQuery($employee)->getResult();
    }


    /**
     * Add a new employee key relationship
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\EmployeeKey",
     *     statusCodes={
     *         201 = "Returned when a new employee key relationship has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
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
     * Totally update employee key relationship
     * @param Request $request
     * @param int     $id
     * @param int     $employee
     * @return View|\Symfony\Component\Form\Form
     * 
     * @ApiDoc(
     *     input="AppBundle\Form\Type\EmployeeKeyType",
     *     output="AppBundle\Entity\EmployeeKey",
     *     statusCodes={
     *         204 = "Returned when an existing employee key relationship has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
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
     * Update employee key relationship
     * @param Request $request
     * @param int     $rkey
     * @param int     $employee
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\EmployeeKeyType",
     *     output="AppBundle\Entity\Employeeey",
     *     statusCodes={
     *         204 = "Returned when an existing employee key relationship has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
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
     * Delete a employee key relationship
     * @param int $id
     * @param int $employee
     * @return View
     *
     * @ApiDoc(
     *     statusCodes={
     *         204 = "Returned when an existing employee key relationship has been successful deleted",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function deleteKeysAction($employee, $rkey)
    {
        $key = $this->getKeyRepository()->deleteEmployeeKeyQuery($employee, $rkey)->getResult();
        if ($key == 0) {
            return new View("This id $rkey doesnt exist");
        }
        return new View("Deleted relationship $rkey");
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
