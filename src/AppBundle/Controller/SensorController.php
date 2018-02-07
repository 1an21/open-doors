<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sensor;
use AppBundle\Entity\Repository\SensorRepository;
use AppBundle\Form\Type\SensorType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SensorController
 * @package AppBundle\Controller
 *
 * @RouteResource("Sensor")
 */
class SensorController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual Sensor
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     */
    public function getAction($id)
    {
        $sensor = $this->getSensorRepository()->createFindOneByIdQuery($id)->getOneOrNullResult();
        if ($sensor === null) {
            return new Response(sprintf('Dont exist sensor with id %s', $id));
        }
        return $sensor;
    }

    /**
     * Gets a collection of Sensors
     *
     * @return array
     *
     */
    public function cgetAction()
    {
        return $this->getSensorRepository()->createFindAllQuery()->getResult();
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function postAction(Request $request)
    {
        $form = $this->createForm(SensorType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        /**
         * @var $Sensor Sensor
         */
        $sensor = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($sensor);
        $em->flush();

        $routeOptions = [
            'id' => $sensor->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     */
    public function putAction(Request $request, $id)
    {
        /**
         * @var $Sensor Sensor
         */
        $sensor = $this->getSensorRepository()->find($id);

        if ($sensor === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(SensorType::class, $sensor, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $sensor->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
    }


    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     */
    public function patchAction(Request $request, $id)
    {
        /**
         * @var $Sensor Sensor
         */
        $sensor = $this->getSensorRepository()->find($id);

        if ($sensor === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(SensorType::class, $sensor, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $sensor->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
    }


    /**
     * @param int $id
     * @return View
     *
     */
    public function deleteAction($id)
    {
        $sensor = $this->getSensorRepository()->deleteQuery($id)->getResult();
        if ($sensor == 0) {
            return new Response(sprintf('This id %s doesnt exist', $id));
        }
        return new Response(sprintf('Deleted user #%s', $id));
    }

    /**
     * @return SensorRepository
     */
    private function getSensorRepository()
    {
        return $this->get('crv.doctrine_entity_repository.sensor');
    }
}
