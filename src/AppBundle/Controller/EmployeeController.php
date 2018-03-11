<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Repository\EmployeeRepository;
use AppBundle\Form\Type\EmployeeType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
/**
 * Class EmployeeController
 * @package AppBundle\Controller
 *
 * @RouteResource("Employee")
 */
class EmployeeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual Employee
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Employee",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction($id)
    {
        $employee= $this->getEmployeeRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();
        if ($employee === null) {
            return new Response(sprintf('Dont exist employee with id %s', $id));
        }
        return $employee;
    }

    /**
     * Gets a collection of Employees
     *
     * @return array
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Employee",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction()
    {
        return $this->getEmployeeRepository()->createFindAllQuery()->getResult();
    }

    /**
     * Add a new employee
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     output="AppBundle\Entity\Employee",
     *     statusCodes={
     *         201 = "Returned when a new employee has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(EmployeeType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $employee = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($employee);
        $em->flush();

        $routeOptions = [
            'id' => $employee->getId(),

            '_format' => $request->get('_format'),
        ];

        $id=$employee->getId();

        $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
        return $this->getEmployeeRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();


    }

    /**
     * Totally update employee
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\EmployeeType",
     *     output="AppBundle\Entity\Employee",
     *     statusCodes={
     *         204 = "Returned when an existing Employee has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAction(Request $request, $id)
    {

        $employee = $this->getEmployeeRepository()->find($id);

        if ($employee === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(EmployeeType::class, $employee, [
            'csrf_protection' => false,]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $employee->getId(),
            '_format' => $request->get('_format'),
        ];

        $id=$employee->getId();
        $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);
        return $this->getEmployeeRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();


        return $this->routeRedirectView('', $routeOptions, Response::HTTP_OK);

    }


    /**
     * Update employee
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="AppBundle\Form\Type\EmployeeType",
     *     output="AppBundle\Entity\Employee",
     *     statusCodes={
     *         204 = "Returned when an existing employee has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function patchAction(Request $request, $id)
    {
        /**
         * @var $employee Employee
         */
        $employee = $this->getEmployeeRepository()->find($id);
        if ($employee === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(EmployeeType::class, $employee, [
            'csrf_protection' => false,
        ]);
        $form->submit($request->request->all(), false);
        if (!$form->isValid()) {
            return $form;
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $routeOptions = [
            'id' => $employee->getId(),
            '_format' => $request->get('_format'),
        ];

        $id=$employee->getId();
        $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
        return $this->getEmployeeRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);

    }


    /**
     * Delete employee
     * @param int $id
     * @return View
     *
     * @ApiDoc(
     *     statusCodes={
     *         204 = "Returned when an existing Employee has been successful deleted",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function deleteAction($id)
    {
        $employee = $this->getEmployeeRepository()->deleteQuery($id)->getResult();
        if ($employee == 0) {
            return new View("Doent exist $id");
        }
        return new View("Deleted user $id");
    }

    /**
     * @return EmployeeRepository
     */
    private function getEmployeeRepository()
    {
        return $this->get('crv.doctrine_entity_repository.employee');
    }
}
